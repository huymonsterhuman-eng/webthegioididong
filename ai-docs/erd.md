# ERD — TheGioiDiDong Clone

## Entities

| Entity | Thuộc tính chính |
|--------|-----------------|
| categories | PK id, name, slug, FK parent_id→categories |
| brands | PK id, name, slug |
| products | PK id, name, slug, price, stock, FK brand_id→brands, FK category_id→categories |
| product_images | PK id, FK product_id→products, path, is_primary |
| collections | PK id, name, slug, FK parent_id→collections |
| users | PK id, username, email, status |
| addresses | PK id, FK user_id→users, name, phone, address, is_default |
| vouchers | PK id, code, type, discount_amount |
| partners | PK id, name, type (supplier/shipping_provider) |
| orders | PK id, FK user_id→users, FK voucher_id→vouchers, FK partner_id→partners, total, status, payment_method |
| order_details | PK id, FK order_id→orders, FK product_id→products, quantity, price_at_purchase |
| goods_receipts | PK id, FK supplier_id→partners, FK user_id→users |
| goods_receipt_details | PK id, FK goods_receipt_id→goods_receipts, FK product_id→products, quantity, remaining_quantity, import_price |
| goods_issues | PK id, FK order_id→orders, status |
| goods_issue_details | PK id, FK goods_issue_id→goods_issues, FK goods_receipt_detail_id→goods_receipt_details, FK product_id→products, quantity |
| post_categories | PK id, name, slug |
| posts | PK id, title, slug, FK post_category_id→post_categories, FK author_id→users |
| banners | PK id, title, image, link |
| reviews | PK id, FK user_id→users, FK product_id→products, rating (1-5) |
| activity_logs | PK id, FK user_id→users, action, subject_type, subject_id (polymorphic) |

**Pivot tables:**
- `collection_product` (FK collection_id→collections, FK product_id→products)
- `user_voucher` (FK user_id→users, FK voucher_id→vouchers, is_used)

---

## Quan hệ

| # | Động từ | Từ | Đến | Cardinality |
|---|---------|-----|------|-------------|
| 1 | chứa | categories | categories | 1:N (self-ref) |
| 2 | thuộc | products | categories | N:1 |
| 3 | thuộc | products | brands | N:1 |
| 4 | có | products | product_images | 1:N |
| 5 | thuộc | products | collections | N:M |
| 6 | chứa | collections | collections | 1:N (self-ref) |
| 7 | có | users | addresses | 1:N |
| 8 | lưu | users | vouchers | N:M |
| 9 | đặt | users | orders | 1:N |
| 10 | viết | users | reviews | 1:N |
| 11 | chứa | orders | order_details | 1:N |
| 12 | mua | order_details | products | N:1 |
| 13 | áp dụng | orders | vouchers | N:1 |
| 14 | giao | orders | partners | N:1 |
| 15 | tạo | orders | goods_issues | 1:1 |
| 16 | nhập | goods_receipts | partners | N:1 |
| 17 | lập | goods_receipts | users | N:1 |
| 18 | chứa | goods_receipts | goods_receipt_details | 1:N |
| 19 | thuộc | goods_receipt_details | products | N:1 |
| 20 | chứa | goods_issues | goods_issue_details | 1:N |
| 21 | trích | goods_issue_details | goods_receipt_details | N:1 |
| 22 | xuất | goods_issue_details | products | N:1 |
| 23 | thuộc | posts | post_categories | N:1 |
| 24 | viết | posts | users | N:1 |
| 25 | đánh giá | reviews | products | N:1 |
| 26 | thực hiện | activity_logs | users | N:1 |
| 27 | gắn | activity_logs | orders / goods_receipts | N:1 (polymorphic) |
