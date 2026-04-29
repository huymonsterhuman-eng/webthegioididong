# Database Schema — TheGioiDiDong Clone

Dự án sử dụng **MySQL**. Dưới đây là schema đầy đủ của tất cả bảng, quan hệ và business logic quan trọng.

> **Lưu ý:** Không có bảng `stock_movements`. Tồn kho được theo dõi qua `goods_receipt_details.remaining_quantity` và `products.stock` (tự động cập nhật bởi `GoodsReceiptDetailObserver`).

---

## 1. Hệ thống Sản phẩm & Danh mục

### `categories`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| name | string | |
| slug | string, unique | |
| parent_id | FK → categories.id | Null = danh mục gốc; hỗ trợ cấu trúc cha/con |
| description | text, nullable | |
| image | string, nullable | |
| is_active | boolean, default true | |
| sort_order | integer, default 0 | |

### `brands`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| name | string | |
| slug | string, unique | |
| logo | string, nullable | |
| description | string, nullable | |
| is_active | boolean, default true | |

### `products`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| name | string | |
| slug | string | |
| sku | string, unique, nullable | |
| price | decimal(15,2) | |
| sale_price | decimal(15,2), nullable | |
| image | string, nullable | Ảnh chính |
| description | text, nullable | |
| screen | string, nullable | Thông số kỹ thuật |
| chip | string, nullable | |
| camera | string, nullable | |
| battery | string, nullable | |
| os | string, nullable | |
| weight | integer, nullable | Grams |
| brand_id | FK → brands.id, nullable | nullOnDelete |
| category_id | FK → categories.id, nullable | nullOnDelete |
| stock | integer, default 0 | Tự động cập nhật bởi Observer |
| views | integer, default 0 | |
| is_featured | boolean, default false | |
| deleted_at | timestamp, nullable | Soft delete |

**Relationships:** `belongsTo(Category)`, `belongsTo(Brand)`, `hasMany(ProductImage)`, `hasOne(ProductImage where is_primary)`, `hasMany(OrderDetail)`, `belongsToMany(Collection)`, `hasMany(Review)`

### `product_images`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| product_id | FK → products.id | cascadeOnDelete |
| path | string | URL/path ảnh |
| sort_order | integer, default 0 | |
| is_primary | boolean, default false | |

### `collections`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| name | string | |
| slug | string, unique | |
| image | string, nullable | |
| description | text, nullable | |
| parent_id | FK → collections.id, nullable | Hỗ trợ nested collections |
| is_active | boolean, default true | |
| show_on_home | boolean, default false | Hiển thị trên trang chủ |
| sort_order | integer, default 0 | |

**Bảng pivot:** `collection_product` (collection_id, product_id)

---

## 2. Hệ thống Người dùng & Phân quyền

### `users`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| username | string, unique | |
| email | string, unique, nullable | |
| email_verified_at | timestamp, nullable | |
| password | string | |
| full_name | string, nullable | |
| phone | string(20), nullable | |
| avatar | string, nullable | |
| gender | string, nullable | |
| birthday | date, nullable | |
| status | enum: active/banned/unverified, default active | |
| remember_token | string, nullable | |

**Relationships:** `hasMany(Order)`, `hasMany(Review)`, `hasMany(Address)`, `belongsToMany(Voucher)` via `user_voucher`

### `addresses`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| user_id | FK → users.id | cascadeOnDelete |
| name | string | Tên người nhận |
| phone | string(20) | |
| address | string | |
| is_default | boolean, default false | |

### Spatie Permission Tables
`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` — quản lý phân quyền admin qua `spatie/laravel-permission`.

---

## 3. Hệ thống Đơn hàng & Thanh toán

### `orders`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| user_id | FK → users.id | cascadeOnDelete |
| subtotal | decimal(15,2), nullable | Tổng tiền hàng chưa tính phí |
| discount_amount | decimal(15,2), nullable | |
| shipping_fee | decimal(12,2), default 0 | |
| total | decimal(15,2) | |
| shipping_name | string, nullable | |
| shipping_address | string(500), nullable | |
| shipping_phone | string(50), nullable | |
| shipping_method | string, nullable | |
| status | enum: pending/confirmed/shipping/delivered/cancelled | |
| payment_method | enum: cod/vnpay/momo, default cod | |
| payment_status | enum: unpaid/paid/refunded, default unpaid | |
| voucher_id | FK → vouchers.id, nullable | nullOnDelete |
| partner_id | FK → partners.id, nullable | Nhà vận chuyển; nullOnDelete |
| tracking_number | string, nullable | Mã vận đơn |
| admin_note | text, nullable | |
| delivered_at | timestamp, nullable | Auto-set khi status = delivered |
| cancelled_at | timestamp, nullable | Auto-set khi status = cancelled |

**Code đơn hàng:** `ORD-YYYYMMDD-NNN` (attribute được tạo tự động)

**Relationships:** `belongsTo(User)`, `belongsTo(Voucher)`, `belongsTo(Partner)`, `hasMany(OrderDetail)`, `morphMany(ActivityLog, 'subject')`

### `order_details`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| order_id | FK → orders.id | cascadeOnDelete |
| product_id | FK → products.id, nullable | nullOnDelete |
| product_name | string, nullable | **Snapshot** tên SP tại thời điểm mua |
| product_image | string, nullable | **Snapshot** ảnh SP tại thời điểm mua |
| quantity | integer | |
| price_at_purchase | decimal(15,2) | **Snapshot** giá tại thời điểm mua |

### `vouchers`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| code | string, unique | |
| name | string | |
| type | enum: fixed/percent | Giảm cố định hoặc phần trăm |
| discount_amount | decimal(15,2) | |
| min_order_value | decimal(15,2), default 0 | Giá trị đơn hàng tối thiểu |
| max_discount | decimal(15,2), nullable | Giới hạn tối đa khi dùng percent |
| started_at | datetime, nullable | |
| expires_at | datetime, nullable | |
| usage_limit | integer, nullable | Giới hạn tổng số lần dùng |
| used_count | integer, default 0 | |
| is_active | boolean, default true | |

**Methods:** `isValid($orderTotal)`, `calculateDiscount($orderTotal)` — xử lý logic percent + max_discount.

### `user_voucher` (pivot)
| Cột | Ghi chú |
|-----|---------|
| user_id, voucher_id | unique(user_id, voucher_id) — mỗi user chỉ lưu 1 lần / voucher |
| is_used | boolean, default false |
| used_at | timestamp, nullable |
| order_id | integer, nullable |

---

## 4. Hệ thống Quản lý Kho (FIFO)

### `partners`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| name | string | |
| type | enum: supplier/shipping_provider | |
| phone | string, nullable | |
| email | string, nullable | |
| address | text, nullable | |
| is_active | boolean, default true | |

### `goods_receipts` (Phiếu nhập kho)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| supplier_id | FK → partners.id | cascadeOnDelete |
| user_id | FK → users.id | Người lập phiếu |
| total_amount | decimal(15,2), default 0 | |
| note | text, nullable | |

### `goods_receipt_details` (Chi tiết nhập — Batch/Lô hàng)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| goods_receipt_id | FK → goods_receipts.id | cascadeOnDelete |
| product_id | FK → products.id | cascadeOnDelete |
| quantity | integer | Số lượng nhập ban đầu |
| **remaining_quantity** | integer, default 0 | **Key FIFO** — số còn lại trong lô này |
| import_price | decimal(15,2) | Giá nhập lô này |

> `GoodsReceiptDetailObserver` tự động: set `remaining_quantity = quantity` khi tạo; tăng/giảm `products.stock` khi quantity hoặc remaining_quantity thay đổi; từ chối xóa nếu hàng đã bán.

### `goods_issues` (Phiếu xuất kho)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| order_id | FK → orders.id | cascadeOnDelete |
| type | string, nullable | 'auto' (khi order→shipping) hoặc 'manual' |
| author_id | FK → users.id, nullable | Người tạo phiếu thủ công |
| note | text, nullable | |
| total_cogs | decimal(15,2), default 0 | Tổng Cost of Goods Sold |
| status | enum: completed/cancelled | |

### `goods_issue_details` (Chi tiết xuất — Audit trail FIFO)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| goods_issue_id | FK → goods_issues.id | cascadeOnDelete |
| goods_receipt_detail_id | FK → goods_receipt_details.id | Batch nguồn (FIFO reference) |
| product_id | FK → products.id | cascadeOnDelete |
| quantity | integer | Số lượng xuất từ batch này |
| import_price | decimal(15,2) | Giá nhập từ batch nguồn |
| total_price | decimal(15,2) | quantity × import_price |

---

## 5. Hệ thống Blog & Nội dung

### `post_categories`
| Cột | Kiểu |
|-----|------|
| id | PK |
| name | string |
| slug | string, unique |

### `posts`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| title | string | |
| slug | string, unique | |
| post_category_id | FK → post_categories.id, nullable | |
| author_id | FK → users.id, nullable | |
| image | string, nullable | |
| excerpt | text, nullable | |
| content | longText, nullable | |
| is_published | boolean, default false | |
| published_at | datetime, nullable | |
| views | integer, default 0 | |

### `banners`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| title | string, nullable | |
| image | string | |
| link | string, nullable | |
| sort_order | integer, default 0 | |
| is_active | boolean, default true | |
| author_id | FK → users.id, nullable | |

### `reviews`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| user_id | FK → users.id | cascadeOnDelete |
| product_id | FK → products.id | cascadeOnDelete |
| rating | tinyInteger (1–5) | |
| comment | text, nullable | |
| images | string, nullable | JSON array các đường dẫn ảnh |
| is_hidden | boolean, default false | Admin có thể ẩn |
| admin_reply | text, nullable | Phản hồi từ admin |

**Unique constraint:** `(user_id, product_id)` — mỗi user chỉ review 1 lần / sản phẩm.

---

## 6. Hệ thống Audit & Logging

### `activity_logs`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id | PK | |
| user_id | FK → users.id, nullable | nullOnDelete |
| action | string | e.g., 'created_order', 'update_receipt' |
| action_type | string, default 'system' | 'inventory', 'order', 'system' |
| description | text | |
| subject_type | string, nullable | Polymorphic — e.g., 'App\\Models\\Order' |
| subject_id | integer, nullable | Polymorphic ID |
| properties | JSON, nullable | Metadata bổ sung |

> Tự động xóa log cũ hơn **90 ngày** (`Prunable`).

---

## 7. Sơ đồ quan hệ

```
users (1)
├── orders (1:N)
│   └── order_details (1:N)
│       └── products (N:1, withTrashed)
├── reviews (1:N) → products (N:1)
├── addresses (1:N)
└── vouchers (N:M via user_voucher)

products (1)
├── category (N:1) → categories (self-ref: parent_id)
├── brand (N:1) → brands
├── productImages (1:N)
├── collections (N:M via collection_product)
└── reviews (1:N)

orders (1)
├── user (N:1)
├── voucher (N:1, nullable)
├── partner (N:1, nullable) → partners
├── orderDetails (1:N)
├── goodsIssue (1:1, nullable)
└── activityLogs (morph)

goods_receipts (1)
├── supplier (N:1) → partners
├── user (N:1)
└── details (1:N) → goods_receipt_details
    └── goodsIssueDetails (1:N) → goods_issue_details

goods_issues (1)
├── order (N:1)
└── details (1:N) → goods_issue_details
    └── goodsReceiptDetail (N:1) ← FIFO batch reference
```

---

## 8. Business Logic Đặc biệt

### FIFO Inventory Flow
```
GoodsReceipt (nhập hàng)
  └─► GoodsReceiptDetail [quantity=100, remaining_quantity=100, import_price=X]
         ↑ Observer tự động: products.stock += 100

Order status → shipping
  └─► Order::handleStatusChange()
        └─► GoodsIssue (type=auto) được tạo
              └─► InventoryService::reduceStock()
                    - Lấy GoodsReceiptDetails theo created_at ASC (cũ nhất trước)
                    - Dùng lockForUpdate() tránh race condition
                    - Tạo GoodsIssueDetail cho mỗi batch dùng
                    - Decrement remaining_quantity từng batch
                    - Observer tự động: products.stock -= quantity

Order status → cancelled
  └─► GoodsIssue.status = 'cancelled'
  └─► Increment lại remaining_quantity các batch
  └─► Hoàn voucher: Voucher.used_count--, user_voucher.is_used = false
```

### Order Lifecycle
```
pending → confirmed → shipping → delivered
                               ↘ cancelled
```
- Timestamps `delivered_at` / `cancelled_at` được set tự động qua Model boot events.
- Logic xử lý đặt trong `Order::handleStatusChange()` — **không** xử lý trực tiếp trong Controller.

### Voucher Calculation
- `type = 'fixed'`: giảm đúng `discount_amount`.
- `type = 'percent'`: giảm `discount_amount`% của total, tối đa `max_discount` (nếu có).
- `isValid()` kiểm tra: `is_active`, `started_at`, `expires_at`, `usage_limit`, `min_order_value`.

### Decimal Precision
Tất cả cột tài chính dùng `decimal(15,2)` để xử lý giá trị VNĐ lớn (tránh overflow với `decimal(10,2)`).
