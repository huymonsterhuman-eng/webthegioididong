# AI README - Project Overview

Tài liệu này cung cấp cái nhìn tổng quan kỹ thuật cho dự án TheGioiDiDong Clone, tối ưu hóa cho AI Assistant và Developers.

## Tech Stack
- **Backend:** Laravel 12 (PHP 8.2+)
- **Admin Panel:** Filament v3 (TALL Stack - Tailwind, Alpine.js, Laravel, Livewire)
- **Frontend:** Laravel Blade + Alpine.js + Tailwind CSS (Vite)
- **Database:** MySQL
- **Packages Quan Trọng:**
  - `spatie/laravel-permission`: Quản lý phân quyền (Roles/Permissions).
  - `spatie/laravel-sitemap`: Tự động tạo sitemap.
  - `laravel/breeze`: Hệ thống Authentication cơ bản (frontend user).

---

## Project Structure

```text
webthegioididong/
├── ai-docs/                    # Tài liệu đặc tả cung cấp ngữ cảnh cho AI
│   ├── CLAUDE.md               # Tổng quan dự án, cấu trúc, tech stack
│   ├── database.md             # Schema DB đầy đủ, quan hệ, business logic kho
│   ├── progress.md             # Tiến độ tính năng (Đã xong / Đang làm)
│   └── rule.md                 # Quy tắc coding, luật làm việc của AI
├── app/
│   ├── Console/                # Các lệnh Artisan tự tạo
│   ├── Filament/               # Toàn bộ logic Admin Panel
│   │   ├── Resources/          # 21 Filament Resources (CRUD admin)
│   │   ├── Widgets/            # 10 Dashboard Widgets (Charts, Stats)
│   │   ├── Pages/              # Custom pages (Dashboard)
│   │   └── Traits/             # HasResourcePermission (phân quyền Resource)
│   ├── Http/
│   │   ├── Controllers/        # 13 Controllers xử lý Frontend + Admin
│   │   └── Middleware/         # Can thiệp HTTP request
│   ├── Models/                 # 20 Eloquent Models
│   ├── Observers/              # Model Observers (side effects tự động)
│   │   ├── OrderObserver.php
│   │   ├── GoodsReceiptObserver.php
│   │   └── GoodsReceiptDetailObserver.php
│   ├── Services/               # Business logic layer
│   │   ├── InventoryService.php    # FIFO stock deduction
│   │   └── ActivityLogService.php  # Ghi nhật ký hệ thống
│   └── Providers/              # Service Providers (AppServiceProvider, AdminPanelProvider)
├── config/                     # Cấu hình hệ thống
├── database/
│   ├── migrations/             # ~47 migration files
│   ├── factories/              # Mock data factories
│   └── seeders/                # Dữ liệu mẫu
├── public/                     # Web root (index.php, compiled assets)
├── resources/
│   ├── css/ & js/              # Source Tailwind CSS và Alpine.js
│   └── views/                  # 43 Blade templates
│       ├── layouts/            # app.blade.php, account.blade.php, guest.blade.php
│       ├── components/         # 14 reusable Blade components
│       └── pdf/                # Invoice PDF template
├── routes/
│   ├── web.php                 # Tất cả routes Frontend + Admin
│   └── auth.php                # Routes Breeze authentication
└── storage/                    # File uploads, cache, logs
```

---

## Controllers Frontend (`app/Http/Controllers/`)

| Controller | Mô tả |
|-----------|-------|
| `HomeController` | Trang chủ — collections, hot products, latest posts |
| `ProductController` | Chi tiết sản phẩm & tìm kiếm |
| `CategoryController` | Danh sách sản phẩm theo danh mục |
| `CollectionController` | Danh sách sản phẩm theo bộ sưu tập |
| `CartController` | Giỏ hàng, checkout, VNPay/MoMo callbacks, apply voucher |
| `BlogController` | Danh sách & chi tiết bài viết |
| `AccountController` | Dashboard tài khoản, profile, địa chỉ, đổi mật khẩu |
| `MyOrderController` | Xem & hủy đơn hàng của user |
| `UserVoucherController` | Xem & lưu voucher của user |
| `ReviewController` | Gửi đánh giá sản phẩm |
| `Admin/OrderInvoiceController` | Tải hóa đơn PDF (admin only) |

---

## Filament Resources (`app/Filament/Resources/`)

| Resource | Đối tượng quản lý |
|----------|------------------|
| `ProductResource` | Sản phẩm (với gallery ảnh, specs) |
| `CategoryResource` | Danh mục (hỗ trợ cha/con) |
| `BrandResource` | Thương hiệu |
| `CollectionResource` | Bộ sưu tập sản phẩm |
| `OrderResource` | Đơn hàng, trạng thái, tracking |
| `VoucherResource` | Mã giảm giá |
| `ReviewResource` | Đánh giá sản phẩm |
| `UserResource` | Tài khoản người dùng |
| `RoleResource` | Vai trò (Spatie) |
| `PartnerResource` | Đối tác (nhà cung cấp & vận chuyển) |
| `ShippingProviderResource` | Nhà vận chuyển (subset of Partners) |
| `GoodsReceiptResource` | Phiếu nhập kho |
| `GoodsReceiptDetailResource` | Chi tiết phiếu nhập kho |
| `GoodsIssueResource` | Phiếu xuất kho |
| `GoodsIssueDetailResource` | Chi tiết phiếu xuất kho |
| `InventoryResource` | Tổng quan tồn kho (view-only) |
| `PostResource` | Bài viết blog |
| `BannerResource` | Banner trang chủ |
| `ActivityLogResource` | Nhật ký hoạt động hệ thống |
| `OrderActivityResource` | Nhật ký liên quan đơn hàng |
| `SystemActivityResource` | Nhật ký hệ thống |

---

## Filament Widgets (`app/Filament/Widgets/`)

| Widget | Mô tả |
|--------|-------|
| `StatsOverview` | KPI chính: Doanh thu, Đơn hàng, Sản phẩm, Người dùng |
| `LatestOrdersWidget` | Bảng đơn hàng mới nhất |
| `OrderStatsWidget` | Thống kê đơn hàng theo trạng thái |
| `RevenueChart` | Biểu đồ doanh thu theo thời gian |
| `SalesByCategoryChart` | Doanh thu phân theo danh mục |
| `OrdersByStatusChart` | Tỷ lệ đơn hàng theo trạng thái |
| `TopProductsWidget` | Top sản phẩm bán chạy |
| `DeadStockWidget` | Sản phẩm tồn kho lâu không bán |
| `LowRatedProducts` | Sản phẩm bị đánh giá thấp |
| `StockMovementChart` | Biểu đồ biến động nhập/xuất kho |

---

## Core Logic

### Authentication
- **Frontend User:** Laravel Breeze — login, register, email verify, password reset.
- **Admin:** Filament built-in auth (`/admin/login`).

### Admin Panel (Filament v3)
- CRUD nhanh cho tất cả entities.
- `HasResourcePermission` trait — kiểm tra Spatie permission trên từng Resource.
- Dashboard với 10 widgets thống kê và biểu đồ.

### Inventory Management (FIFO)
- Nhập kho qua `GoodsReceipt` → tạo `GoodsReceiptDetail` (batch/lô hàng) với `remaining_quantity`.
- Khi Order chuyển sang `shipping`, `Order::handleStatusChange()` tự động tạo `GoodsIssue` và gọi `InventoryService::reduceStock()`.
- `InventoryService` lấy batch theo `created_at ASC` (FIFO), dùng `lockForUpdate()` chống race condition.
- Hủy đơn (`cancelled`) → tự động hoàn lại `remaining_quantity` cho các batch.

### Order Lifecycle
```
pending → confirmed → shipping → delivered
                   ↘ cancelled (hoàn kho + hoàn voucher)
```

### Product Routing (SEO)
- URL: `/{categorySlug}/{productSlug}` — thân thiện SEO.

### Payment Gateway
- **COD:** Mặc định, không cần tích hợp thêm.
- **VNPay & MoMo:** Tích hợp cơ bản — redirect + callback handling trong `CartController`.

### Activity Logging
- `ActivityLogService::log()` — ghi nhật ký cho mọi hành động quan trọng.
- Polymorphic `morphMany` — `ActivityLog` có thể gắn với Order, GoodsReceipt, v.v.
- Tự động xóa log cũ hơn 90 ngày (`Prunable`).

### Collections
- Bộ sưu tập sản phẩm nested (hỗ trợ cha/con), N:M với Products.
- `show_on_home = true` → hiển thị trên trang chủ.

### Review System
- User chỉ được review 1 lần / sản phẩm (unique constraint).
- Admin có thể ẩn (`is_hidden`) hoặc phản hồi (`admin_reply`).

### State Management (Frontend)
- Alpine.js cho giỏ hàng flyout, mobile menu, và các tương tác phía client.
