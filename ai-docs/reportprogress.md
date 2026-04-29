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

## Đợt 2 — Đơn hàng & Kho (8 bảng) ⏳ CHƯA LÀM

- [ ] collection_product
- [ ] orders
- [ ] order_details
- [ ] vouchers
- [ ] user_voucher
- [ ] partners
- [ ] goods_receipts
- [ ] goods_receipt_details

---

## Đợt 3 — Nội dung & Hệ thống (7 bảng) ⏳ CHƯA LÀM

- [ ] goods_issues
- [ ] goods_issue_details
- [ ] reviews
- [ ] posts
- [ ] post_categories
- [ ] banners
- [ ] activity_logs

---

## Tổng tiến độ: 8 / 23 bảng hoàn thành
