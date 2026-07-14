<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Các tool mà Gemini có thể tự động gọi khi trả lời khách hàng.
 * Mỗi method nhận args (đã validate qua schema Gemini), trả về array JSON-serializable.
 */
class ChatBotToolService
{
    /**
     * Trả về schema tools cho Gemini API (functionDeclarations).
     */
    public function getToolsDeclaration(): array
    {
        return [
            [
                'functionDeclarations' => [
                    [
                        'name' => 'search_products',
                        'description' => 'Tìm sản phẩm đang bán theo từ khoá (tên sản phẩm, thương hiệu). Dùng khi khách hỏi giá, còn hàng không, tìm sản phẩm gì đó.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'query' => ['type' => 'string', 'description' => 'Từ khoá tìm kiếm (ví dụ: "iPhone 15", "laptop Dell").'],
                                'limit' => ['type' => 'integer', 'description' => 'Số lượng kết quả tối đa (mặc định 5).'],
                            ],
                            'required' => ['query'],
                        ],
                    ],
                    [
                        'name' => 'get_product_details',
                        'description' => 'Xem thông tin chi tiết 1 sản phẩm theo slug. Dùng khi cần trả lời chi tiết về cấu hình, giá, tồn kho của 1 sản phẩm cụ thể.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'slug' => ['type' => 'string', 'description' => 'Slug của sản phẩm (ví dụ: "iphone-15").'],
                            ],
                            'required' => ['slug'],
                        ],
                    ],
                    [
                        'name' => 'get_hot_products',
                        'description' => 'Lấy danh sách sản phẩm được xem nhiều/bán chạy. Dùng khi khách hỏi "shop có gì hot", "sản phẩm bán chạy".',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'limit' => ['type' => 'integer', 'description' => 'Số lượng sản phẩm (mặc định 5).'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'get_active_vouchers',
                        'description' => 'Lấy danh sách voucher/mã giảm giá đang có hiệu lực. Dùng khi khách hỏi "có mã giảm giá gì không", "khuyến mãi gì".',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => new \stdClass(),
                        ],
                    ],
                    [
                        'name' => 'get_my_orders',
                        'description' => 'Xem danh sách đơn hàng của khách hàng đang đăng nhập. Dùng khi khách hỏi "đơn của em", "đơn gần nhất". Cần user đã đăng nhập.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'status' => [
                                    'type' => 'string',
                                    'description' => 'Lọc theo trạng thái (tuỳ chọn): pending, confirmed, preparing, shipping, delivered, cancelled.',
                                ],
                                'limit' => ['type' => 'integer', 'description' => 'Số lượng đơn tối đa (mặc định 5).'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'get_order_details',
                        'description' => 'Xem chi tiết 1 đơn hàng cụ thể của user đang đăng nhập. Dùng khi khách hỏi cụ thể về 1 mã đơn hoặc muốn xem sản phẩm trong đơn.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'order_code' => [
                                    'type' => 'string',
                                    'description' => 'Mã đơn hàng dạng ORD-YYYYMMDD-XXX hoặc số ID.',
                                ],
                            ],
                            'required' => ['order_code'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Dispatch — gọi tool tương ứng theo tên.
     * Trả về array. Nếu lỗi trả về ['error' => '...'].
     */
    public function call(string $name, array $args): array
    {
        try {
            return match ($name) {
                'search_products'      => $this->searchProducts($args),
                'get_product_details'  => $this->getProductDetails($args),
                'get_hot_products'     => $this->getHotProducts($args),
                'get_active_vouchers'  => $this->getActiveVouchers(),
                'get_my_orders'        => $this->getMyOrders($args),
                'get_order_details'    => $this->getOrderDetails($args),
                default                => ['error' => "Tool '{$name}' không tồn tại."],
            };
        } catch (\Throwable $e) {
            Log::error('ChatBotToolService error', [
                'tool' => $name,
                'args' => $args,
                'error' => $e->getMessage(),
            ]);
            return ['error' => 'Có lỗi xảy ra khi truy vấn dữ liệu.'];
        }
    }

    /* ================== TOOLS ================== */

    private function searchProducts(array $args): array
    {
        $query = trim((string) ($args['query'] ?? ''));
        $limit = min(10, max(1, (int) ($args['limit'] ?? 5)));

        if ($query === '') {
            return ['error' => 'Thiếu từ khoá tìm kiếm.'];
        }

        $products = Product::active()
            ->with(['category', 'brand'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        if ($products->isEmpty()) {
            return ['results' => [], 'message' => 'Không tìm thấy sản phẩm phù hợp.'];
        }

        return [
            'count' => $products->count(),
            'results' => $products->map(fn ($p) => $this->productToArray($p))->all(),
        ];
    }

    private function getProductDetails(array $args): array
    {
        $slug = trim((string) ($args['slug'] ?? ''));
        if ($slug === '') {
            return ['error' => 'Thiếu slug sản phẩm.'];
        }

        $product = Product::active()
            ->with(['category', 'brand'])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return ['error' => "Không tìm thấy sản phẩm với slug '{$slug}'."];
        }

        return array_merge($this->productToArray($product), [
            'description' => $this->truncate((string) $product->description, 400),
            'specs' => array_filter([
                'screen' => $product->screen,
                'chip' => $product->chip,
                'camera' => $product->camera,
                'battery' => $product->battery,
                'os' => $product->os,
            ]),
            'average_rating' => round((float) $product->averageRating(), 1),
        ]);
    }

    private function getHotProducts(array $args): array
    {
        $limit = min(10, max(1, (int) ($args['limit'] ?? 5)));

        return Cache::remember("chatbot:hot_products:{$limit}", 300, function () use ($limit) {
            $products = Product::active()
                ->with(['category', 'brand'])
                ->orderBy('views', 'desc')
                ->limit($limit)
                ->get();

            return [
                'count' => $products->count(),
                'results' => $products->map(fn ($p) => $this->productToArray($p))->all(),
            ];
        });
    }

    private function getActiveVouchers(): array
    {
        return Cache::remember('chatbot:active_vouchers', 300, function () {
            $vouchers = Voucher::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->where(function ($q) {
                    $q->whereNull('started_at')->orWhere('started_at', '<=', now());
                })
                ->orderBy('expires_at', 'asc')
                ->limit(10)
                ->get();

            if ($vouchers->isEmpty()) {
                return ['results' => [], 'message' => 'Hiện chưa có voucher nào đang hoạt động.'];
            }

            return [
                'count' => $vouchers->count(),
                'results' => $vouchers->map(function (Voucher $v) {
                    return [
                        'code' => $v->code,
                        'name' => $v->name,
                        'type' => $v->type,
                        'discount' => $v->type === 'percent'
                            ? "{$v->discount_amount}%" . ($v->max_discount ? " (tối đa " . number_format((float) $v->max_discount, 0, ',', '.') . "đ)" : '')
                            : number_format((float) $v->discount_amount, 0, ',', '.') . 'đ',
                        'min_order_value' => $v->min_order_value ? number_format((float) $v->min_order_value, 0, ',', '.') . 'đ' : null,
                        'expires_at' => $v->expires_at?->format('d/m/Y H:i'),
                    ];
                })->all(),
            ];
        });
    }

    private function getMyOrders(array $args): array
    {
        $user = auth()->user();
        if (!$user) {
            return ['error' => 'Cần đăng nhập để xem đơn hàng.'];
        }

        $limit = min(10, max(1, (int) ($args['limit'] ?? 5)));
        $status = $args['status'] ?? null;
        $validStatuses = ['pending', 'confirmed', 'preparing', 'shipping', 'delivered', 'cancelled'];

        $q = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($status && in_array($status, $validStatuses, true)) {
            $q->where('status', $status);
        }

        $orders = $q->get();

        if ($orders->isEmpty()) {
            return ['results' => [], 'message' => 'Anh/chị chưa có đơn hàng nào phù hợp.'];
        }

        return [
            'count' => $orders->count(),
            'results' => $orders->map(fn ($o) => $this->orderSummary($o))->all(),
        ];
    }

    private function getOrderDetails(array $args): array
    {
        $user = auth()->user();
        if (!$user) {
            return ['error' => 'Cần đăng nhập để xem chi tiết đơn.'];
        }

        $raw = trim((string) ($args['order_code'] ?? ''));
        if ($raw === '') {
            return ['error' => 'Thiếu mã đơn hàng.'];
        }

        // Parse: ORD-YYYYMMDD-XXX → lấy XXX làm ID, hoặc chấp nhận số ID trực tiếp
        $orderId = null;
        if (preg_match('/^ORD-\d{8}-(\d+)$/i', $raw, $m)) {
            $orderId = (int) $m[1];
        } elseif (ctype_digit($raw)) {
            $orderId = (int) $raw;
        }

        if (!$orderId) {
            return ['error' => "Mã đơn '{$raw}' không hợp lệ. Định dạng đúng: ORD-YYYYMMDD-XXX."];
        }

        $order = Order::with(['orderDetails.product', 'voucher'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)   // ⚠️ security: chỉ đơn của user hiện tại
            ->first();

        if (!$order) {
            return ['error' => "Không tìm thấy đơn hàng, hoặc đơn này không thuộc về anh/chị."];
        }

        return array_merge($this->orderSummary($order), [
            'items' => $order->orderDetails->map(function ($d) {
                return [
                    'product_name' => $d->product_name ?? $d->product?->name ?? 'Sản phẩm',
                    'quantity' => (int) $d->quantity,
                    'price' => number_format((float) $d->price_at_purchase, 0, ',', '.') . 'đ',
                    'subtotal' => number_format((float) $d->price_at_purchase * $d->quantity, 0, ',', '.') . 'đ',
                ];
            })->all(),
            'shipping_address' => $order->shipping_address,
            'shipping_phone' => $order->shipping_phone,
            'shipping_provider' => $order->shipping_provider_name,
            'tracking_number' => $order->tracking_number,
            'voucher_code' => $order->voucher?->code,
            'discount_amount' => (float) $order->discount_amount
                ? number_format((float) $order->discount_amount, 0, ',', '.') . 'đ' : null,
        ]);
    }

    /* ================== HELPERS ================== */

    private function productToArray(Product $p): array
    {
        return [
            'name' => $p->name,
            'slug' => $p->slug,
            'sku' => $p->sku,
            'price' => number_format((float) $p->price, 0, ',', '.') . 'đ',
            'sale_price' => $p->sale_price ? number_format((float) $p->sale_price, 0, ',', '.') . 'đ' : null,
            'stock' => (int) $p->stock,
            'available_stock' => (int) $p->available_stock,
            'category' => $p->category?->name,
            'brand' => $p->brand?->name,
            'is_featured' => (bool) $p->is_featured,
            'url' => url("/{$p->category?->slug}/{$p->slug}"),
        ];
    }

    private function orderSummary(Order $o): array
    {
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'preparing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao thành công',
            'cancelled' => 'Đã huỷ',
        ];

        return [
            'order_code' => 'ORD-' . Carbon::parse($o->created_at)->format('Ymd') . '-' . str_pad($o->id, 3, '0', STR_PAD_LEFT),
            'id' => $o->id,
            'status' => $o->status,
            'status_label' => $statusLabels[$o->status] ?? $o->status,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'total' => number_format((float) $o->total, 0, ',', '.') . 'đ',
            'created_at' => $o->created_at?->format('d/m/Y H:i'),
            'delivered_at' => $o->delivered_at?->format('d/m/Y H:i'),
            'cancelled_at' => $o->cancelled_at?->format('d/m/Y H:i'),
        ];
    }

    private function truncate(string $text, int $len): string
    {
        $stripped = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
        return mb_strlen($stripped) > $len ? mb_substr($stripped, 0, $len) . '...' : $stripped;
    }
}
