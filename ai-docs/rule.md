# AI Coding Rules & Guidelines

Tài liệu này định nghĩa các quy tắc cốt lõi dành cho AI trong quá trình làm việc với dự án TheGioiDiDong Clone. Các nguyên tắc này đảm bảo hiệu suất, tránh lan man và duy trì tính toàn vẹn của mã nguồn.

## Nguyên tắc cốt lõi khi làm việc (Working Rules)

> [!IMPORTANT]
> **Rule 1: Không đọc tất cả file — chỉ đọc những file thực sự cần thiết cho nhiệm vụ hiện tại.**
> Thay vì quét toàn bộ thư mục hoặc các file không liên quan, hãy sử dụng tư duy phân tích để giới hạn phạm vi, chỉ sử dụng tool xem file với những file có khả năng liên quan trực tiếp đến lỗi hoặc tính năng đang thực hiện.

> [!IMPORTANT]
> **Rule 2: Thiếu thông tin thì hỏi, không tự suy đoán và làm tiếp.**
> Bất cứ khi nào logic nghiệp vụ, yêu cầu hoặc cách cấu hình chưa rõ ràng, AI **phải** tạm dừng việc code, đặt câu hỏi cho người dùng để lấy xác nhận hoặc thông tin bổ sung trước khi triển khai, tuyệt đối không tự bịa logic.

## Quy tắc Coding (Coding Convention)

### 1. Naming Convention
- **Model/Class:** Sử dụng `PascalCase` (Ví dụ: `ProductResource`, `OrderController`).
- **Database Columns / Biến (Variables):** Sử dụng `snake_case` (Ví dụ: `remaining_quantity`, `is_active`).

### 2. Filament First
- Đối với Trang Quản Trị (Admin Panel), **luôn ưu tiên** sử dụng các thành phần của Filament (Resources, Custom Pages, Widgets, Actions, Infolists).
- Hạn chế viết Controller hoặc Blade thuần cho Admin trừ khi tính năng đó vượt quá khả năng của Filament.

### 3. Frontend & Styling
- Sử dụng **Atomic CSS** (Tailwind CSS) trực tiếp trong các file Blade component.
- Hạn chế tối đa việc viết CSS tùy chỉnh (Custom CSS) ra file riêng trừ những hiệu ứng đặc thù. Sử dụng Alpine.js cho các hiệu ứng tương tác (như Dropdown, Flyout cart).

### 4. Database & Migrations
- Tất cả các file migration phải mang tính `idempotent` (chạy nhiều lần không gây lỗi).
- Luôn đảm bảo toàn vẹn dữ liệu bằng cách sử dụng khóa ngoại (`Foreign keys`), và đánh chỉ mục (`Indexes`) cho các cột thường xuyên tìm kiếm.

### 5. Services Layer
- Business logic phức tạp **phải** đặt trong `app/Services/`, không được viết trực tiếp vào Controller hoặc Model.
- Ví dụ: Logic trừ kho FIFO → `InventoryService::reduceStock()`, không viết trong `OrderController` hay `Order::boot()`.
- Controller chỉ nhận request, gọi Service, trả về response.

### 6. Observer Pattern
- Mọi **side effect** của Model (tự động cập nhật stock, ghi log) phải dùng Observer trong `app/Observers/`.
- Không gọi trực tiếp `ActivityLogService::log()` hay cập nhật `products.stock` từ bên trong Controller.
- Ví dụ hiện có: `GoodsReceiptDetailObserver` tự động cộng/trừ `products.stock` khi `remaining_quantity` thay đổi.
