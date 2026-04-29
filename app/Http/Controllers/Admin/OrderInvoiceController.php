<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderInvoiceController extends Controller
{
    /**
     * Generate and download order invoice PDF.
     */
    public function download(Order $order)
    {
        // Simple security: only allow authenticated users (Filament handles resource auth, 
        // but we add a check here as well for this standalone route)
        if (!auth()->check()) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.order_invoice', [
            'order' => $order->load(['orderDetails.product', 'partner']),
        ]);

        return $pdf->download("Invoce_{$order->order_code}.pdf");
    }
}
