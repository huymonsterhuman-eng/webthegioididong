# Tiến trình Báo cáo Thiết kế Cơ sở Dữ liệu (Mức Vật lý)

## Định dạng mỗi bảng
Thuộc tính | Kiểu dữ liệu | Ràng buộc | Not Null
- Not Null: ghi **x** nếu bắt buộc, **AUTO_INCREMENT** cho cột id tự tăng
- Ràng buộc: PRIMARY KEY / FOREIGN KEY (bảng_tham_chiếu.id) / UNIQUE

---

## Đợt 1 — Người dùng & Danh mục (8 bảng) ✅ HOÀN THÀNH

- [x] roles
- [x] users
- [x] addresses
- [x] categories
- [x] brands
- [x] products
- [x] product_images
- [x] collections

---

## Đợt 2 — Đơn hàng & Kho (8 bảng) ✅ HOÀN THÀNH


- [ ] collection_product
- [ ] orders
- [ ] order_details
- [ ] vouchers
- [ ] user_voucher
- [ ] partners
- [ ] goods_receipts
- [ ] goods_receipt_details

---

## Đợt 3 — Nội dung & Hệ thống (7 bảng) ✅ HOÀN THÀNH


- [ ] goods_issues
- [ ] goods_issue_details
- [ ] reviews
- [ ] posts
- [ ] post_categories
- [ ] banners
- [ ] activity_logs

---

## Tổng tiến độ: 23/ 23 bảng hoàn thành

---
---

# Kế hoạch & Tiến độ Viết Test Case

## Tổng quan

| Nhóm | Tên nhóm | Tiến độ |
|------|----------|---------|
| A | Xác thực & Tài khoản (A1–A8) | 8 / 8 |
| B | Duyệt & Tìm kiếm sản phẩm (B1–B5) | 5 / 5 |
| C | Giỏ hàng & Mua sắm (C1–C7) | 7 / 7 |
| D | Đơn hàng & Voucher — User (D1–D4) | 4 / 4 |
| E | Đánh giá & Blog (E1–E4) | 4 / 4 |
| F | Admin: Đăng nhập & Phân quyền (F1–F3) | 3 / 3 |
| G | Admin: Danh mục & Sản phẩm (G1–G4) | 4 / 4 |
| H | Admin: Quản lý Đơn hàng (H1–H4) | 4 / 4 |
| I | Admin: Kho hàng (I1–I5) | 5 / 5 |
| J | Admin: Nội dung & Tiện ích (J1–J5) | 5 / 5 |

**Tổng: 42 / 42 chức năng**

---

## Checklist chi tiết

### Nhóm A — Xác thực & Tài khoản
- [x] A1 — Đăng ký tài khoản
- [x] A2 — Đăng nhập
- [x] A3 — Đăng xuất
- [x] A4 — Quên mật khẩu / Đặt lại mật khẩu
- [x] A5 — Xác minh email
- [x] A6 — Cập nhật thông tin cá nhân
- [x] A7 — Quản lý địa chỉ giao hàng
- [x] A8 — Đổi mật khẩu

### Nhóm B — Duyệt & Tìm kiếm sản phẩm
- [x] B1 — Trang chủ
- [x] B2 — Tìm kiếm sản phẩm
- [x] B3 — Danh sách sản phẩm theo danh mục
- [x] B4 — Danh sách sản phẩm theo bộ sưu tập
- [x] B5 — Chi tiết sản phẩm

### Nhóm C — Giỏ hàng & Mua sắm
- [x] C1 — Thêm sản phẩm vào giỏ
- [x] C2 — Cập nhật số lượng trong giỏ
- [x] C3 — Xóa sản phẩm khỏi giỏ
- [x] C4 — Áp dụng mã voucher
- [x] C5 — Checkout & Đặt hàng (COD)
- [x] C6 — Thanh toán VNPay
- [x] C7 — Thanh toán MoMo

### Nhóm D — Đơn hàng & Voucher (User)
- [x] D1 — Xem danh sách & chi tiết đơn hàng
- [x] D2 — Hủy đơn hàng
- [x] D3 — Xem danh sách voucher
- [x] D4 — Lưu voucher mới

### Nhóm E — Đánh giá & Blog
- [x] E1 — Gửi đánh giá sản phẩm
- [x] E2 — Giới hạn 1 đánh giá / sản phẩm
- [x] E3 — Xem danh sách bài viết
- [x] E4 — Xem chi tiết bài viết

### Nhóm F — Admin: Đăng nhập & Phân quyền
- [x] F1 — Đăng nhập Admin
- [x] F2 — Phân quyền vai trò (Roles & Permissions)
- [x] F3 — Quản lý tài khoản người dùng

### Nhóm G — Admin: Danh mục & Sản phẩm
- [x] G1 — CRUD Danh mục
- [x] G2 — CRUD Thương hiệu
- [x] G3 — CRUD Bộ sưu tập
- [x] G4 — CRUD Sản phẩm

### Nhóm H — Admin: Quản lý Đơn hàng
- [x] H1 — Xem & cập nhật trạng thái đơn hàng
- [x] H2 — Vòng đời đơn hàng (pending → delivered / cancelled)
- [x] H3 — Hủy đơn → hoàn kho + hoàn voucher
- [x] H4 — Tải hóa đơn PDF

### Nhóm I — Admin: Kho hàng
- [x] I1 — Tạo phiếu nhập kho
- [x] I2 — Tự động cập nhật tồn kho khi nhập
- [x] I3 — Xuất kho FIFO khi đơn sang "shipping"
- [x] I4 — Hoàn kho khi hủy đơn
- [x] I5 — Xem tổng quan tồn kho

### Nhóm J — Admin: Nội dung & Tiện ích
- [x] J1 — CRUD Voucher
- [x] J2 — Quản lý Đánh giá (ẩn/hiện, phản hồi)
- [x] J3 — CRUD Blog & Banner
- [x] J4 — Xem nhật ký hoạt động
- [x] J5 — Tự động xóa log > 90 ngày
