<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->order_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .shop-info { float: left; }
        .invoice-info { float: right; text-align: right; }
        .clear { clear: both; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #1a56db; margin-bottom: 10px; }
        .customer-section { margin-top: 30px; margin-bottom: 30px; }
        .section-title { font-size: 16px; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table th { background: #f8f9fa; padding: 10px; border-bottom: 1px solid #eee; }
        table td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { float: right; width: 250px; margin-top: 20px; }
        .total-section div { display: flex; justify-content: space-between; padding: 5px 0; }
        .grand-total { font-weight: bold; font-size: 16px; color: #d32f2f; border-top: 2px solid #eee; padding-top: 10px; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #777; }
        .barcode { margin-top: 20px; text-align: center; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 5px; font-size: 10px; color: #fff; background: #666; }
        .status-paid { background: #28a745; }
        .shipping-info { margin-top: 20px; background: #fdfdfd; padding: 15px; border: 1px dashed #ccc; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="shop-info">
                <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>
                <strong>HUY MONSTER MOBILE</strong><br>
                Địa chỉ: 123 Đường Công Nghệ, Q. Bình Thạnh, TP. HCM<br>
                Điện thoại: 1900 1234<br>
                Email: support@huymonster.com
            </div>
            <div class="invoice-info">
                Mã đơn: <strong>{{ $order->order_code }}</strong><br>
                Ngày đặt: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}<br>
                PTTT: {{ $order->payment_method }}<br>
                @if($order->payment_status === 'paid')
                    <span class="status-badge status-paid">ĐÃ THANH TOÁN</span>
                @else
                    <span class="status-badge">CHƯA THANH TOÁN</span>
                @endif
            </div>
            <div class="clear"></div>
        </div>

        <div class="customer-section">
            <div class="section-title">Thông tin khách hàng</div>
            Tên: {{ $order->shipping_name }}<br>
            Số điện thoại: {{ $order->shipping_phone }}<br>
            Địa chỉ nhận: {{ $order->shipping_address }}
        </div>

        <div class="section-title">Chi tiết đơn hàng</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40%">Sản phẩm</th>
                    <th style="text-align: center">SL</th>
                    <th style="text-align: right">Đơn giá</th>
                    <th style="text-align: right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product_name ?? ($detail->product->name ?? 'N/A') }}</td>
                    <td style="text-align: center">{{ $detail->quantity }}</td>
                    <td style="text-align: right">{{ number_format($detail->price_at_purchase, 0, ',', '.') }} ₫</td>
                    <td style="text-align: right">{{ number_format($detail->quantity * $detail->price_at_purchase, 0, ',', '.') }} ₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div style="width: 100%">
                <span style="float: left">Tiền hàng:</span>
                <span style="float: right">{{ number_format($order->subtotal, 0, ',', '.') }} ₫</span>
                <div class="clear"></div>
            </div>
            @if($order->discount_amount > 0)
            <div style="width: 100%">
                <span style="float: left">Giảm giá:</span>
                <span style="float: right">- {{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                <div class="clear"></div>
            </div>
            @endif
            <div style="width: 100%">
                <span style="float: left">Phí vận chuyển:</span>
                <span style="float: right">{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} ₫</span>
                <div class="clear"></div>
            </div>
            <div class="grand-total" style="width: 100%">
                <span style="float: left">TỔNG CỘNG:</span>
                <span style="float: right">{{ number_format($order->total, 0, ',', '.') }} ₫</span>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>

        @if($order->tracking_number)
        <div class="shipping-info">
            <strong>Thông tin vận chuyển:</strong><br>
            Đơn vị: {{ $order->partner?->name ?? 'Giao hàng nhanh' }}<br>
            Mã vận đơn (Tracking): <strong>{{ $order->tracking_number }}</strong>
        </div>
        @endif

        <div class="footer">
            Cảm ơn quý khách đã mua hàng tại Huy Monster Mobile!<br>
            (Đây là hóa đơn điện tử tự động)
        </div>
    </div>
</body>
</html>
