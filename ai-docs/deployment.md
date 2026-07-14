# Thông tin Triển khai (Deployment)

> File này ghi lại toàn bộ cấu hình production để **các session AI mới đọc là biết ngay** — không cần dò lại từ đầu. Nếu có thay đổi hạ tầng (đổi bucket, đổi platform, đổi env var...), CẬP NHẬT FILE NÀY.

---

## 1. Tổng quan

Hệ thống production gồm **3 dịch vụ đám mây** hoạt động độc lập:

| Thành phần | Dịch vụ | Vai trò |
|---|---|---|
| App server | **Railway** (PaaS) | Chạy Laravel 12, xử lý toàn bộ logic |
| Database | **Railway MySQL** plugin | Lưu dữ liệu có cấu trúc (users, products, orders...) |
| File storage | **Cloudflare R2** | Lưu ảnh (products, banners, reviews, collections) |

**URL production:** https://web-production-d2dee.up.railway.app
**Admin panel:** https://web-production-d2dee.up.railway.app/admin

---

## 2. Railway (App + MySQL)

### App service
- Nền tảng: Railway PaaS
- Build: **Railpack** (tự nhận diện Laravel) — **Railpack BỎ QUA `Procfile`**, phải dùng `railway.json`
- Auto-deploy: push lên nhánh `main` → Railway tự build + deploy
- HTTPS: Railway cấp SSL tự động

### File `railway.json` (thư mục gốc)
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan config:clear && php artisan cache:clear && php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}
```
→ Mỗi lần deploy tự động chạy migration. **KHÔNG import SQL thủ công.**

### Database
- **MySQL** (KHÔNG phải PostgreSQL) — cả local XAMPP lẫn Railway đều dùng MySQL
- Railway inject sẵn biến `MYSQL_URL`
- Migration đồng bộ tự động qua `migrate --force`

---

## 3. Cloudflare R2 (File Storage)

- **Bucket:** `webthegioididong`
- **URL công khai:** `https://pub-6f5f77338df44599af9009541a809498.r2.dev`
- **Endpoint API:** `https://46d22886572975ca7ea5bd71933f4888.r2.cloudflarestorage.com`
- Tích hợp qua Laravel Filesystem — driver **S3-compatible**
- Disk name trong `config/filesystems.php`: `r2`

### Thư mục trong bucket
| Thư mục | Nội dung |
|---|---|
| `products/` | Ảnh chính + ảnh phụ sản phẩm |
| `banners/` | Banner trang chủ |
| `reviews/` | Ảnh đánh giá của khách |
| `collections/` | Ảnh bộ sưu tập |
| `img/` | Ảnh cũ migrate từ XAMPP (legacy path) |

### Lệnh upload ảnh cũ (đã chạy 1 lần)
```bash
php artisan app:upload-images-to-r2
```
→ Upload 277 file từ `storage/app/public/` local lên R2.

---

## 4. Biến môi trường trên Railway

**KHÔNG commit `.env` lên Git.** Set trực tiếp trên Railway Dashboard → Variables.

### Ứng dụng
```
APP_NAME=TheGioiDiDong
APP_ENV=production
APP_DEBUG=false           # ⚠️ phải là false trên production
APP_URL=https://web-production-d2dee.up.railway.app
APP_KEY=base64:...
```

### Database
```
MYSQL_URL=...             # Railway auto-inject
```

### Cloudflare R2
```
FILESYSTEM_DISK=r2
CLOUDFLARE_R2_ACCESS_KEY_ID=...
CLOUDFLARE_R2_SECRET_ACCESS_KEY=...
CLOUDFLARE_R2_BUCKET=webthegioididong
CLOUDFLARE_R2_ENDPOINT=https://46d22886572975ca7ea5bd71933f4888.r2.cloudflarestorage.com
CLOUDFLARE_R2_URL=https://pub-6f5f77338df44599af9009541a809498.r2.dev
```

### Thanh toán
```
VNPAY_TMN_CODE=...
VNPAY_HASH_SECRET=...
VNPAY_URL=...
MOMO_PARTNER_CODE=...
MOMO_ACCESS_KEY=...
MOMO_SECRET_KEY=...
```

---

## 5. QUY TẮC KHI CODE — tránh lỗi ảnh trên R2

### ✅ ĐÚNG
```php
// Filament ImageColumn / FileUpload
Tables\Columns\ImageColumn::make('image')
    ->disk(config('filesystems.default'))   // BẮT BUỘC — nếu không sẽ trỏ về local
    ->circular();

Forms\Components\FileUpload::make('image')
    ->disk(config('filesystems.default'))
    ->directory('products');
```

```php
// Blade view — dùng Storage::url()
<img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}">
```

```php
// Placeholder / default image
$defaultImageUrl = Storage::url('img/placeholder.jpg');
```

### ❌ SAI (sẽ bị vỡ ảnh trên production)
```php
// KHÔNG dùng asset('storage/...') vì trỏ về server Railway (không có file)
<img src="{{ asset('storage/' . $product->image) }}">

// KHÔNG dùng url('storage/img/...') hardcoded
<img src="{{ url('storage/img/' . $img) }}">

// KHÔNG dùng ImageColumn không có ->disk()
Tables\Columns\ImageColumn::make('image')->circular();   // ❌
```

### Nguyên tắc vàng
- **Luôn** dùng `Storage::url()` hoặc `->disk(config('filesystems.default'))`
- **Không** hardcode path `storage/`, `img/`, `public/`

---

## 6. Quy trình deploy khi có thay đổi

```bash
git add .
git commit -m "..."
git push origin main
```

Railway tự động:
1. Kéo code mới từ GitHub
2. `composer install --no-dev`
3. Chạy `startCommand` trong `railway.json`:
   - `migrate --force` → cập nhật DB schema
   - `config:clear` + `cache:clear` → xóa cache cũ
   - `php artisan serve` → khởi động server
4. Website live sau ~2–3 phút

---

## 7. Sự cố đã gặp & cách xử lý

| Sự cố | Nguyên nhân | Cách fix |
|---|---|---|
| Ảnh vỡ trên production | Code dùng `asset('storage/...')` hoặc `url('storage/...')` | Đổi sang `Storage::url()` |
| ImageColumn Filament không hiện ảnh | Thiếu `->disk(config(...))` | Thêm `->disk(config('filesystems.default'))` |
| Migration không chạy khi deploy | Railpack bỏ qua Procfile | Tạo `railway.json` với `deploy.startCommand` |
| Mixed Content (HTTP/HTTPS) | Railway proxy | Thêm `trustProxies(at: '*')` vào `bootstrap/app.php` |
| `intl` extension missing | Railpack không cài | Bypass — thay `->money()` bằng `number_format()` |
| Mất Secret Key R2 | Cloudflare chỉ hiện 1 lần | Xóa token cũ, tạo token mới, cập nhật env var |

---

## 8. Local development

- File `.env` (KHÔNG commit) — DB pointing to XAMPP MySQL
- `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=thegioididong_new`
- Storage disk local mặc định — không dùng R2 (trừ khi test)
- Chạy: `php artisan serve` + `npm run dev`

---

**Cập nhật lần cuối:** 2026-07-02
