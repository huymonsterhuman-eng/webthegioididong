# Project Progress (Tiến trình dự án)

Tài liệu này theo dõi tiến độ chung của dự án TheGioiDiDong Clone, giúp nắm bắt nhanh những tính năng đã hoàn thiện và những công việc còn tồn đọng.

## Đã hoàn thành (Done)

**Core E-commerce**
- Danh mục sản phẩm (Categories, nested cha/con)
- Sản phẩm với gallery ảnh, specs kỹ thuật, soft delete
- Bộ sưu tập sản phẩm (Collections, nested, hiển thị trang chủ)
- Thương hiệu (Brands)
- Giỏ hàng & Quy trình Thanh toán (Checkout)
- Định tuyến SEO: `/{categorySlug}/{productSlug}`

**Hệ thống Người dùng**
- Authentication (Laravel Breeze): Đăng ký, đăng nhập, quên mật khẩu
- Quản lý tài khoản: Cập nhật profile, đổi mật khẩu
- Địa chỉ giao hàng (Addresses): Thêm/sửa/xóa, đặt mặc định

**Hệ thống Đơn hàng**
- Quản lý đơn hàng đầy đủ (CRUD, trạng thái, tracking)
- Order Lifecycle tự động: pending → confirmed → shipping → delivered / cancelled
- Hủy đơn hàng phía user (MyOrderController) với hoàn kho tự động
- PDF Invoice: Xuất hóa đơn từ Admin Panel

**Hệ thống Voucher**
- Logic giảm giá linh hoạt: theo phần trăm (có `max_discount`) hoặc số tiền cố định
- User có thể lưu & dùng voucher; theo dõi trạng thái dùng qua `user_voucher` pivot

**Quản lý Kho (Inventory — FIFO)**
- Nhập kho (Goods Receipt) theo lô (batch) với import price
- Xuất kho tự động (Goods Issue) khi đơn chuyển sang Shipping
- Thuật toán FIFO dùng `goods_receipt_details.remaining_quantity`
- COGS tracking qua `goods_issue_details`
- Hoàn kho tự động khi đơn bị hủy

**Admin Dashboard (Filament v3)**
- 21 Filament Resources cho toàn bộ entities
- 10 Widgets thống kê: Doanh thu, Đơn hàng, Kho, Sản phẩm bán chạy, v.v.
- Hệ thống phân quyền chi tiết (Role & Permission via Spatie)
- Banner Management: Quản lý banner trang chủ

**Hệ thống Review**
- User đánh giá sản phẩm (rating 1–5, comment, ảnh)
- Giới hạn 1 review / user / sản phẩm
- Admin có thể ẩn review (`is_hidden`) hoặc phản hồi (`admin_reply`)

**Hệ thống Blog**
- Bài viết với danh mục (PostCategory), ảnh thumbnail, excerpt, nội dung
- Lên lịch đăng bài (`published_at`), theo dõi lượt xem

**Activity Logging**
- Audit trail tự động cho Order và Inventory qua Polymorphic morphMany
- `ActivityLogService` và các Observers
- Tự động dọn dẹp log > 90 ngày (`Prunable`)

**Payment Gateway (Cơ bản)**
- VNPay: Redirect & callback handling
- MoMo: Redirect & callback handling

---

## Đang phát triển / Cần cải thiện (In Progress / To-Do)

- **Performance:** Bổ sung Eager Loading (giải quyết N+1 query problem), thêm database indexes cho các cột tìm kiếm thường xuyên.
- **Payment Gateway:** Hoàn thiện luồng VNPay/MoMo — test đầy đủ edge cases (timeout, refund, webhook bảo mật).
- **Email Notifications:** Gửi email xác nhận đơn hàng, cập nhật trạng thái giao hàng (field `email_verified_at` đã có nhưng luồng notification chưa triển khai).
- **Mobile UX:** Cải thiện responsive cho màn hình nhỏ, tối ưu Header và Menu.
- **Refactor Order Cancellation:** Logic hủy đơn hiện tồn tại ở 2 nơi (`Order::handleStatusChange()` và `MyOrderController`) — cần hợp nhất về 1 điểm để tránh inconsistency.
