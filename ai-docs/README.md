# TheGioiDiDong Clone (Laravel 12 + Filament 3)

Đây là dự án chuyển đổi toàn diện từ web thương mại điện tử PHP thuần sang môi trường sinh thái hiện đại của Laravel.

## 📌 Thông tin dự án

Dự án này là một phiên bản clone của website Thế Giới Di Động, tích hợp đầy đủ tính năng dành cho cửa hàng bán lẻ trực tuyến. 
Các công nghệ và tính năng chính:
- **Ngôn ngữ & Framework:** Laravel 12, PHP 8.2+.
- **Giao diện người dùng (Frontend):** Trang chủ tùy biến đầy đủ, bộ lọc danh mục sản phẩm, định tuyến sản phẩm linh hoạt (`/{category}/{product}`). Xây dựng với TailwindCSS và Alpine.js.
- **Trang quản trị (Admin Dashboard):** Xây dựng bằng Filament v3 mạnh mẽ.
- **Tính năng mở rộng:** Tích hợp giỏ hàng, xác nhận đơn hàng, chọn nhà vận chuyển, đối tác cung cấp, mã giảm giá (Voucher), và hệ thống bài viết (Blog).

---

## ⚙️ Những thứ cần cài đặt (Yêu cầu hệ thống)

Để chạy dự án này trên môi trường local, máy tính của bạn cần cài đặt các phần mềm sau:

1. **PHP:** Phiên bản `>= 8.2` (Khuyên dùng XAMPP, Laragon hoặc Herd).
2. **Composer:** Trình quản lý thư viện của PHP.
3. **Node.js & npm:** Để biên dịch các tài nguyên frontend (TailwindCSS, JS).
4. **MySQL / MariaDB:** Hệ quản trị cơ sở dữ liệu.

---

## 🚀 Cách chạy dự án (Môi trường Local)

Thực hiện các bước sau để cấu hình và chạy dự án:

### Bước 1: Clone dự án và sao chép cấu hình
Di chuyển vào thư mục dự án và sao chép file cấu hình môi trường:
```bash
cp .env.example .env
```

### Bước 2: Cài đặt các thư viện (Dependencies)
Chạy lệnh sau để cài đặt các package PHP và Node.js:
```bash
composer install
npm install
```

> **Shortcut:** `composer setup` tự động chạy `install`, `key:generate`, `migrate`, `storage:link` cùng lúc.

### Bước 3: Tạo Key cho ứng dụng
```bash
php artisan key:generate
```

### Bước 4: Cấu hình Cơ sở dữ liệu
Mở file `.env` và cập nhật thông tin kết nối tới database MySQL của bạn:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name # Thay bằng tên database của bạn
DB_USERNAME=root               # Tên user MySQL (thuờng là root)
DB_PASSWORD=                   # Mật khẩu (nếu có)
```

### Bước 5: Chạy Migration và Seed dữ liệu (Nếu có)
Tạo các bảng trong database (và thêm dữ liệu mẫu nếu dự án có seed):
```bash
php artisan migrate
# Nếu có seeder thì chạy thêm: php artisan db:seed
```

### Bước 6: Link thư mục Upload (Storage)
Để hình ảnh lưu trong `storage/app/public` có thể truy cập được từ bên ngoài:
```bash
php artisan storage:link
```

### Bước 7: Chạy dự án
Mở 2 terminal tại thư mục dự án, chạy 2 lệnh sau song song:

**Terminal 1 (Trình duyệt web backend):**
```bash
php artisan serve
```

**Terminal 2 (Biên dịch Frontend trực tiếp):**
```bash
npm run dev
```

> **Shortcut:** `composer dev` tự động chạy song song cả 4 tiến trình: server, queue, logs, và vite.

Truy cập trang web:
- **Trang người dùng:** `http://localhost:8000`
- **Trang quản trị (Filament):** `http://localhost:8000/admin`

### Bước 8: Tạo tài khoản Admin (lần đầu)
```bash
php artisan make:filament-user
```
Làm theo hướng dẫn để tạo tài khoản admin đầu tiên. Sau khi đăng nhập, vào **Roles** để gán quyền cho tài khoản.

---

## 📦 Deployment (Đưa lên Hosting/Server)

Khi triển khai lên môi trường Production (như Hostinger/cPanel):
1. Chạy `composer install --no-dev --optimize-autoloader`.
2. Chạy `npm run build` để tối ưu hóa Frontend.
3. Nén mã nguồn (Bỏ qua thư mục `/node_modules`) và tải lên thư mục `public_html` của Domain.
4. Cấu hình DB trong `.env`, đổi `APP_ENV=production` và `APP_DEBUG=false`.
5. Tạo symlink cho ảnh, và trỏ Document Root của Domain vào folder `/public` của Laravel.
6. Xóa cache hệ thống:
   ```bash
   php artisan optimize:clear
   ```
