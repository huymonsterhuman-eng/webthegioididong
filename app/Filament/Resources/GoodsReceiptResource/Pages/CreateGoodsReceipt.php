<?php

namespace App\Filament\Resources\GoodsReceiptResource\Pages;

use App\Filament\Resources\GoodsReceiptResource;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptDetail;
use App\Models\Partner;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;

class CreateGoodsReceipt extends Page
{
    protected static string $resource = GoodsReceiptResource::class;
    protected static string $view = 'filament.resources.goods-receipt.create';

    public ?int $supplier_id = null;
    public string $note = '';

    /** @var array<int, array{product_id: int, import_price: float, quantity: int}> */
    public array $cart = [];

    // Search/filter for product table
    public string $search = '';

    /** List of all products sorted by stock asc */
    #[Computed]
    public function products()
    {
        return Product::orderBy('stock', 'asc')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->get();
    }

    #[Computed]
    public function suppliers()
    {
        return Partner::where('type', 'supplier')->where('is_active', true)->get();
    }

    /** Grand total of the cart */
    #[Computed]
    public function grandTotal(): float
    {
        return collect($this->cart)->sum(fn($item) => (float)($item['quantity'] ?? 0) * (float)($item['import_price'] ?? 0));
    }

    public function addToCart(int $productId): void
    {
        // If already in cart, just increment qty
        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] === $productId) {
                $this->cart[$key]['quantity']++;
                return;
            }
        }

        $product = Product::find($productId);
        if (!$product) return;

        // Get last import price
        $lastDetail = GoodsReceiptDetail::where('product_id', $productId)->latest()->first();
        $lastPrice = $lastDetail ? (float) $lastDetail->import_price : 0;

        $this->cart[] = [
            'product_id' => $productId,
            'product_name' => $product->name,
            'retail_price' => (float) $product->price,
            'import_price' => $lastPrice,
            'quantity' => 1,
        ];
    }

    public function removeFromCart(int $index): void
    {
        array_splice($this->cart, $index, 1);
        $this->cart = array_values($this->cart);
    }

    public function updatedCart(): void
    {
        // Recompute grandTotal – Livewire will auto re-render
    }

    public function saveReceipt(): void
    {
        if (!$this->supplier_id) {
            Notification::make()->title('Vui lòng chọn nhà cung cấp (Supplier).')->danger()->send();
            return;
        }

        if (empty($this->cart)) {
            Notification::make()->title('Giỏ nhập rỗng. Vui lòng thêm ít nhất 1 sản phẩm.')->danger()->send();
            return;
        }

        // Validate each item
        foreach ($this->cart as $item) {
            if (($item['quantity'] ?? 0) < 1 || ($item['import_price'] ?? 0) <= 0) {
                Notification::make()->title('Số lượng và giá nhập phải lớn hơn 0.')->warning()->send();
                return;
            }
        }

        $total = collect($this->cart)->sum(fn($item) => (float)$item['quantity'] * (float)$item['import_price']);

        \Illuminate\Support\Facades\DB::transaction(function () use ($total) {
            $receipt = GoodsReceipt::create([
                'supplier_id' => $this->supplier_id,
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'note' => $this->note,
            ]);

            $detailedItems = [];
            foreach ($this->cart as $item) {
                $detail = GoodsReceiptDetail::create([
                    'goods_receipt_id' => $receipt->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'import_price' => $item['import_price'],
                ]);

                $detailedItems[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'] ?? Product::find($item['product_id'])->name,
                    'quantity' => $item['quantity'],
                    'import_price' => (float)$item['import_price'],
                    'receipt_detail_id' => $detail->id,
                ];
            }

            \App\Services\ActivityLogService::log(
                'create_manual_receipt',
                "Đã lập phiếu nhập kho #{$receipt->id} với " . count($detailedItems) . " loại sản phẩm.",
                'inventory',
                $receipt,
                [
                    'total_amount' => $total,
                    'supplier_id' => $this->supplier_id,
                    'item_count' => count($detailedItems),
                    'detailed_items' => $detailedItems
                ]
            );
        });

        Notification::make()
            ->title('Phiếu nhập đã được lưu thành công!')
            ->success()
            ->send();

        $this->redirect(GoodsReceiptResource::getUrl('index'));
    }
}
