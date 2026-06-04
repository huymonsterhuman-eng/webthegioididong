# V. MÔ TẢ GIAO DIỆN QUẢN TRỊ (ADMIN INTERFACE)

Hệ thống quản trị (Admin Panel) của website TheGioiDiDong Clone được xây dựng hoàn toàn trên nền tảng **Filament v3** — một framework Admin Panel hiện đại dành riêng cho Laravel, tuân theo kiến trúc TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire). Toàn bộ giao diện admin được phân tách độc lập với giao diện người dùng, truy cập qua đường dẫn `/admin`, sử dụng hệ thống xác thực riêng biệt và áp dụng màu chủ đạo là **Amber (vàng cam)** theo cấu hình Filament Color system. Giao diện admin được thiết kế theo tiêu chuẩn SaaS Dashboard hiện đại với thanh điều hướng dọc bên trái, nền tối (dark sidebar), vùng nội dung sáng bên phải và hệ thống phân quyền dựa trên vai trò (Role-Based Access Control — RBAC) thông qua gói Spatie Laravel Permission.

---

## 5.1. Trang Đăng Nhập Admin (`/admin/login`)

Trang đăng nhập admin là cửa ngõ bảo mật của toàn bộ hệ thống quản trị, hoàn toàn tách biệt với trang đăng nhập của người dùng frontend. Giao diện sử dụng layout mặc định của Filament với form đăng nhập căn giữa trang, bao gồm trường Email, Mật khẩu và nút "Đăng nhập". Filament tự động xử lý các biện pháp bảo mật như rate limiting, CSRF protection và session management. Chỉ các tài khoản được cấp quyền truy cập admin (có role được Filament công nhận) mới có thể đăng nhập thành công; các tài khoản người dùng thông thường sẽ bị từ chối với thông báo lỗi tương ứng.

---

## 5.2. Bố Cục Tổng Thể Giao Diện Admin (Shell Layout)

Sau khi đăng nhập thành công, quản trị viên tiếp cận giao diện làm việc chính bao gồm các vùng bố cục sau:

**Thanh điều hướng dọc bên trái (Sidebar Navigation):** Chiếm khoảng 256px chiều rộng, nền màu tối đặc trưng của Filament. Phần đầu sidebar hiển thị logo/tên ứng dụng. Tiếp theo là danh sách điều hướng được phân nhóm thành 5 nhóm chức năng chính (Navigation Groups), mỗi nhóm có thể thu gọn/mở rộng (collapsible). Cuối sidebar là thông tin tài khoản đang đăng nhập kèm nút đăng xuất.

**Thanh tiêu đề trên cùng (Topbar):** Chứa nút thu gọn/mở rộng sidebar, breadcrumb điều hướng cho biết vị trí hiện tại trong hệ thống, nút thông báo và avatar tài khoản admin với dropdown menu.

**Vùng nội dung chính (Main Content Area):** Chiếm toàn bộ không gian còn lại, hiển thị nội dung tương ứng với mục đang được chọn trong sidebar (Dashboard, bảng dữ liệu, form CRUD, v.v.).

**Hệ thống Nhóm Điều Hướng (Navigation Groups):**

| Nhóm | Biểu tượng | Chức năng quản lý |
|---|---|---|
| Tổng quan (Overview) | 📊 | Dashboard thống kê |
| Sản phẩm (Catalog) | 📦 | Sản phẩm, Danh mục, Thương hiệu, Bộ sưu tập |
| Kinh doanh (Sales) | 🛒 | Đơn hàng, Mã giảm giá, Đánh giá |
| Kho & Vận chuyển (Logistics) | 🏭 | Phiếu nhập kho, Phiếu xuất kho, Tồn kho, Nhà vận chuyển |
| Nội dung (Content) | 📝 | Bài viết Blog, Banner |
| Hệ thống (System) | 🔐 | Người dùng, Vai trò, Đối tác, Nhật ký |

---

## 5.3. Trang Dashboard — Tổng Quan Hệ Thống

Dashboard là trang mặc định sau khi đăng nhập, tổng hợp toàn bộ chỉ số vận hành quan trọng của hệ thống thông qua **10 widget** được tổ chức theo thứ tự ưu tiên. Tất cả widgets trên Dashboard đều có kiểm soát quyền truy cập — chỉ hiển thị với tài khoản có quyền `view_reports` hoặc vai trò `super-admin`.

### 5.3.1. Widget Chỉ Số KPI (StatsOverview)

Widget đầu tiên và nổi bật nhất, hiển thị 6 thẻ thống kê tổng quan (Stat Cards) xếp theo lưới ngang:

- **Total Revenue:** Tổng doanh thu toàn thời gian (từ các đơn `delivered`, `shipping`), định dạng số có phân cách hàng nghìn, đơn vị ₫. Badge màu xanh lá.
- **Total Orders:** Tổng số đơn hàng toàn hệ thống. Badge màu Amber (primary).
- **New Users This Month:** Số người dùng đăng ký mới trong tháng hiện tại, tiêu đề tự động cập nhật theo tháng/năm. Badge màu xanh dương (info).
- **Cảnh báo hết hàng (1-4):** Số sản phẩm còn 1-4 đơn vị trong kho. Màu cam (warning) nếu > 0, xanh lá nếu = 0. Thẻ này là liên kết dẫn thẳng đến danh sách sản phẩm đã lọc sẵn theo tiêu chí tồn kho nguy hiểm.
- **Sản phẩm hết hàng (=0):** Số sản phẩm đã hết hoàn toàn. Màu đỏ (danger) nếu > 0. Cũng là liên kết điều hướng nhanh đến danh sách hết hàng.
- **Latest Orders:** Số đơn hàng mới phát sinh trong ngày hôm nay. Badge màu cam.

### 5.3.2. Biểu Đồ Doanh Thu (RevenueChart)

Biểu đồ đường (line chart) thể hiện doanh thu theo từng tháng trong năm hiện tại (12 điểm dữ liệu, trục X là tháng Jan–Dec). Đường biểu đồ màu xanh lá (#10b981) với vùng nền gradient mờ. Chỉ tính doanh thu từ các đơn hàng có trạng thái `delivered`. Tiêu đề tự động cập nhật theo năm hiện tại.

### 5.3.3. Top Sản Phẩm Bán Chạy (TopProductsWidget)

Bảng dữ liệu (table widget) hiển thị Top 5 sản phẩm bán chạy nhất, sắp xếp theo tổng số lượng đã bán (`total_sold`). Mỗi dòng bao gồm: ảnh sản phẩm 36×36px, tên sản phẩm (rút gọn 25 ký tự), số lượng đã bán (badge màu xanh lá) và tồn kho hiện tại (badge màu đỏ/cam/xám tùy ngưỡng).

### 5.3.4. Thống Kê Đơn Hàng (OrderStatsWidget)

Widget thống kê số lượng đơn hàng phân theo từng trạng thái trong pipeline, cho phép quản trị viên nắm được ngay bao nhiêu đơn đang chờ xử lý, đang giao hay đã hoàn thành.

### 5.3.5. Biểu Đồ Phân Phối Đơn Hàng (OrdersByStatusChart)

Biểu đồ tròn (pie/donut chart) thể hiện tỷ lệ phần trăm đơn hàng theo từng trạng thái, giúp nhận diện nhanh bottleneck trong quy trình xử lý đơn.

### 5.3.6. Doanh Thu Theo Danh Mục (SalesByCategoryChart)

Biểu đồ cột (bar chart) so sánh doanh thu giữa các danh mục sản phẩm, hỗ trợ quyết định phân bổ ngân sách nhập hàng và marketing.

### 5.3.7. Bảng Đơn Hàng Mới Nhất (LatestOrdersWidget)

Bảng hiển thị 5–10 đơn hàng gần đây nhất với thông tin mã đơn, tên khách hàng, tổng tiền và trạng thái, kèm liên kết xem chi tiết từng đơn.

### 5.3.8. Dead Stock — Tồn Kho Ứ Đọng (DeadStockWidget)

Widget toàn chiều rộng (full-width) liệt kê Top 5 sản phẩm có tồn kho cao nhưng **không phát sinh bất kỳ đơn hàng nào trong 30 ngày qua**. Mỗi dòng gồm ảnh tròn, tên sản phẩm, số lượng tồn (badge đỏ), giá bán và ngày tạo mã sản phẩm. Đây là công cụ cảnh báo sớm giúp quản trị viên nhận diện hàng tồn kho không hiệu quả để có phương án xử lý kịp thời (giảm giá, tặng kèm, v.v.).

### 5.3.9. Sản Phẩm Đánh Giá Thấp (LowRatedProducts)

Bảng các sản phẩm có điểm đánh giá trung bình thấp, hỗ trợ quản trị viên kiểm soát chất lượng catalog sản phẩm và đưa ra quyết định phản hồi khách hàng hoặc điều chỉnh thông tin sản phẩm.

### 5.3.10. Biểu Đồ Biến Động Nhập/Xuất Kho (StockMovementChart)

Biểu đồ so sánh song song giữa lượng nhập kho (Goods Receipt) và lượng xuất kho (Goods Issue) theo thời gian, giúp theo dõi sức mua và nhịp nhập hàng của hệ thống.

---

## 5.4. Nhóm Sản Phẩm (Catalog) — Quản Lý Danh Mục Sản Phẩm

### 5.4.1. Quản Lý Sản Phẩm (ProductResource)

**Trang danh sách (List Products):** Bảng dữ liệu gồm các cột: Hình ảnh (ảnh vuông 60×60px, lazy loading), Tên sản phẩm (có thể tìm kiếm theo tên và mô tả), Danh mục (có thể sắp xếp), Thương hiệu, Giá bán (định dạng số, có thể sắp xếp) và Tồn kho.

**Bộ lọc nâng cao:** Hệ thống cung cấp 4 bộ lọc: Trashed Filter (xem sản phẩm đã xóa mềm), Lọc theo Thương hiệu (dropdown tìm kiếm), Lọc theo Danh mục (đa chọn), Lọc theo Khoảng giá (hai trường nhập min/max kèm indicator tag hiển thị bộ lọc đang áp dụng) và Lọc theo Tình trạng tồn kho (In Stock / Low Stock 5-10 / Critical 1-4 / Out of Stock).

**Form thêm/sửa sản phẩm:** Form tuyến tính gồm các trường: Tên sản phẩm (tự động tạo slug khi blur), Slug (unique), Danh mục (dropdown quan hệ), Thương hiệu (dropdown), Giá gốc (số, suffix ₫), Giá sale (số), Ảnh chính (FileUpload với image editor tích hợp), Mô tả (Textarea toàn chiều rộng), và các trường kỹ thuật: Màn hình, Chip, Camera, SKU, Trọng lượng, Pin, Hệ điều hành. Trường **Tồn kho** được hiển thị dưới dạng read-only với chú thích cảnh báo: tồn kho chỉ được cập nhật thông qua phiếu nhập kho, không chỉnh sửa trực tiếp.

### 5.4.2. Quản Lý Danh Mục (CategoryResource)

Quản lý danh mục sản phẩm có hỗ trợ cấu trúc cha/con (parent/child). Form tạo danh mục gồm tên, slug, và trường `parent_id` cho phép thiết lập danh mục con.

### 5.4.3. Quản Lý Thương Hiệu (BrandResource)

CRUD đơn giản cho thương hiệu sản phẩm, gồm tên, slug, logo và mô tả.

### 5.4.4. Quản Lý Bộ Sưu Tập (CollectionResource)

Quản lý bộ sưu tập sản phẩm hỗ trợ cấu trúc phân cấp (cha/con) và quan hệ N:M với sản phẩm. Có trường `show_on_home` (Toggle) điều khiển hiển thị trên trang chủ và `sort_order` điều khiển thứ tự xuất hiện trong menu điều hướng.

---

## 5.5. Nhóm Kinh Doanh (Sales) — Quản Lý Vận Hành Bán Hàng

### 5.5.1. Quản Lý Đơn Hàng (OrderResource)

Đây là Resource phức tạp và quan trọng nhất trong hệ thống, với đầy đủ các chế độ: Danh sách, Xem chi tiết (View/Infolist), Tạo mới và Chỉnh sửa (bị khóa — `canEdit = false` để bảo toàn tính nhất quán dữ liệu).

**Trang danh sách đơn hàng:** Bảng gồm các cột: Order Code (mã đơn có thể tìm kiếm), Customer (tên khách hàng), Tổng cộng (định dạng tiền tệ VND), Trạng thái (badge màu ngữ nghĩa), Ngày tạo và Phương thức vận chuyển (ẩn mặc định, toggle). Bộ lọc gồm lọc theo trạng thái và lọc theo khoảng thời gian tạo đơn (date range picker).

**Nhóm thao tác (ActionGroup):** Mỗi đơn hàng có nhóm thao tác "..." cho phép:
- **Xem chi tiết** (`ViewAction`): Mở trang Infolist với thông tin đầy đủ.
- **Xác nhận đơn** (`confirm`): Chuyển `pending → confirmed`. Chỉ hiện khi đơn đang ở `pending`. Yêu cầu xác nhận.
- **Giao hàng** (`ship`): Chuyển sang `shipping`, mở modal nhỏ yêu cầu chọn đơn vị vận chuyển và nhập mã vận đơn (tự động tạo ngẫu nhiên theo format `SHIP-XXXXXXXXXX`).
- **Đã giao thành công** (`delivered`): Chuyển `shipping → delivered`. Chỉ hiện khi đang giao.
- **Hủy đơn** (`cancel`): Chuyển sang `cancelled`. Chỉ áp dụng với `pending` và `confirmed`.
- **In hóa đơn** (`print_invoice`): Mở file PDF hóa đơn trong tab mới.

**Trang xem chi tiết đơn (Infolist):** Bố cục 3 cột (2+1). Cột trái (2/3): Section "Thông tin đơn hàng" (mã đơn có thể copy, trạng thái badge, ngày đặt, phương thức thanh toán, trạng thái thanh toán) và Section "Thông tin vận nhận" (tên người nhận, SĐT, địa chỉ, đơn vị vận chuyển, mã vận đơn có thể copy). Cột phải (1/3): Section "Chi phí" (tiền hàng, giảm giá màu đỏ, phí ship, tổng cộng màu xanh in đậm) và Section "Ghi chú Admin". Bên dưới là hai Relation Manager: danh sách chi tiết sản phẩm và nhật ký hoạt động đơn hàng.

**Form tạo đơn hàng mới (CreateOrder):** Form 3 cột được dùng để admin tạo đơn thay mặt khách hàng. Cột trái (2/3): Section sản phẩm sử dụng Repeater cho phép thêm nhiều sản phẩm (hiển thị tồn kho trong dropdown), Section thông tin giao nhận và Section ghi chú admin. Cột phải (1/3): Section khách hàng (dropdown tìm kiếm + inline tạo khách hàng mới), trạng thái đơn, phương thức thanh toán và trạng thái thanh toán, voucher (dropdown tự tính discount), và Section "Tổng kết chi phí" cập nhật live (tiền hàng, phí ship, tổng cộng).

### 5.5.2. Quản Lý Mã Giảm Giá (VoucherResource)

**Trang danh sách:** Hiển thị mã code (tìm kiếm được), loại giảm giá (số tiền/phần trăm), mức giảm (định dạng thông minh: X₫ hoặc X%), ngày hết hạn, số lần đã dùng và toggle trạng thái khả dụng.

**Form tạo/sửa voucher:** Gồm Mã voucher (unique), Loại giảm giá (Select live — thay đổi validation của trường tiếp theo), Mức giảm (số, max=100 khi loại là phần trăm), Giá trị đơn hàng tối thiểu, Mức giảm tối đa (chỉ dùng cho loại phần trăm để giới hạn số tiền giảm tối đa), Ngày hết hạn (DateTimePicker) và Toggle kích hoạt.

### 5.5.3. Quản Lý Đánh Giá Sản Phẩm (ReviewResource)

Danh sách đánh giá từ người dùng với chức năng xem nội dung, ẩn/hiện đánh giá (`is_hidden` toggle) và thêm phản hồi của quản trị viên (`admin_reply`) — phản hồi sẽ hiển thị ngay dưới đánh giá trên trang chi tiết sản phẩm ở giao diện người dùng.

---

## 5.6. Nhóm Kho & Vận Chuyển (Logistics)

### 5.6.1. Phiếu Nhập Kho (GoodsReceiptResource)

Module nhập kho là cơ sở của hệ thống quản lý tồn kho FIFO. Đây là chức năng duy nhất có thể tăng số lượng tồn kho của sản phẩm.

**Trang danh sách:** Hiển thị mã phiếu (format PR-XXXX), Nhà cung cấp (tìm kiếm được), Người tạo, Tổng giá trị nhập (định dạng VND), Số lượng mặt hàng và Ngày tạo. Mặc định sắp xếp giảm dần theo ngày.

**Form tạo phiếu nhập kho:** Chia thành 2 sections: Section "Receipt Information" gồm dropdown chọn Nhà cung cấp (chỉ liệt kê partners có type = 'supplier' và đang hoạt động) và ô ghi chú. Section "Products" sử dụng Repeater 9 cột cho phép thêm nhiều sản phẩm cùng lúc — mỗi dòng gồm: dropdown chọn sản phẩm (hiển thị tồn kho hiện tại dạng `[Tồn: X] Tên SP`, sắp xếp theo tồn kho tăng dần để ưu tiên nhập hàng sắp hết), Giá niêm yết (read-only, tự điền khi chọn sản phẩm), Giá nhập (tự điền từ lần nhập gần nhất, có thể sửa) và Số lượng nhập.

Khi lưu phiếu, `GoodsReceiptObserver` tự động cộng số lượng vào cột `stock` của từng sản phẩm và tạo các `GoodsReceiptDetail` (lô hàng) để phục vụ cơ chế FIFO khi xuất kho.

### 5.6.2. Phiếu Xuất Kho (GoodsIssueResource)

Phiếu xuất kho được tạo tự động bởi hệ thống thông qua `InventoryService` khi đơn hàng chuyển sang trạng thái `shipping` — quản trị viên không tạo phiếu xuất thủ công. Giao diện chỉ cho phép xem danh sách và chi tiết các phiếu xuất đã được tạo, cung cấp khả năng kiểm toán (audit trail) cho toàn bộ lịch sử xuất kho.

### 5.6.3. Tổng Quan Tồn Kho (InventoryResource)

Đây là Resource chỉ đọc (view-only), không cho phép tạo mới hay chỉnh sửa. Hiển thị bảng tổng quan tồn kho với ảnh, tên sản phẩm, thương hiệu và số lượng tồn hiện tại dưới dạng badge màu ngữ nghĩa: **đỏ** (≤0, hết hàng), **vàng** (≤10, sắp hết), **xanh lá** (>10, đủ hàng).

Bộ lọc gồm lọc theo thương hiệu và lọc theo tình trạng tồn kho (Còn hàng/Hết hàng). Header action đặc biệt: nút **"Đồng bộ lô hàng"** (màu cam) cho phép tái tính toán và phân phối lại tồn kho vào các lô FIFO (GoodsReceiptDetail) — sử dụng khi cần đồng bộ lại dữ liệu sau can thiệp thủ công. Trang xem chi tiết một sản phẩm hiển thị thông tin sản phẩm và danh sách toàn bộ lô hàng (batches) còn remaining_quantity > 0.

### 5.6.4. Quản Lý Nhà Vận Chuyển (ShippingProviderResource)

Quản lý các đối tác cung cấp dịch vụ vận chuyển, được sử dụng trong form tạo đơn hàng và quy trình giao hàng. Mỗi nhà vận chuyển có tên, thông tin liên lạc và trạng thái hoạt động.

---

## 5.7. Nhóm Nội Dung (Content)

### 5.7.1. Quản Lý Bài Viết Blog (PostResource)

CRUD đầy đủ cho module Blog. Form tạo/sửa bài viết gồm: Tiêu đề (tự động tạo slug), Slug (unique), Danh mục bài viết, Ảnh đại diện (FileUpload), Đoạn trích (excerpt), Nội dung đầy đủ (Textarea), Ngày đăng (DateTimePicker) và trạng thái xuất bản. Bảng danh sách hiển thị ảnh thumbnail, tiêu đề, danh mục, ngày đăng và trạng thái.

### 5.7.2. Quản Lý Banner Trang Chủ (BannerResource)

Quản lý các banner hiển thị trong Hero Slider trên trang chủ. Mỗi banner có: Tiêu đề, Ảnh banner (FileUpload), URL đích (liên kết khi click), thứ tự hiển thị (`sort_order`) và trạng thái kích hoạt. Quản trị viên có thể thêm/sửa/xóa banner và thay đổi thứ tự hiển thị mà không cần can thiệp code.

---

## 5.8. Nhóm Hệ Thống (System)

### 5.8.1. Quản Lý Người Dùng (UserResource)

**Trang danh sách người dùng:** Bảng gồm các cột: Tên đăng nhập (in đậm, tìm kiếm được), Họ tên, Email, Số điện thoại, Vai trò (badge màu primary, nhiều vai trò phân cách dấu phẩy), Số đơn hàng (badge xám, count quan hệ), Trạng thái (badge màu: xanh/đỏ/vàng) và Ngày đăng ký (ẩn mặc định).

Bộ lọc: theo trạng thái (Active/Banned/Unverified) và "Đăng ký mới trong tháng". Thao tác hàng (row actions): Xem chi tiết, Sửa thông tin, và nút **Lock/Unlock** (đổi trạng thái banned/active) hiển thị động theo trạng thái hiện tại. Bulk actions: Kích hoạt hàng loạt và Chặn hàng loạt (chỉ dành cho tài khoản có quyền `manage_users`). **Xóa người dùng bị vô hiệu hóa** (`canDelete = false`) để bảo toàn lịch sử.

**Trang xem chi tiết người dùng (Infolist):** Chia thành 3 sections: "Thông tin định danh" (username có thể copy, email, trạng thái badge, vai trò badge — 4 cột), "Hồ sơ người dùng" (họ tên, SĐT, giới tính, ngày sinh — 4 cột) và "Thống kê hoạt động" (tổng đơn hàng, tổng chi tiêu, số đánh giá, ngày gia nhập — 4 cột). Bên dưới là 4 Relation Manager: Đơn hàng (tabbed), Địa chỉ, Đánh giá và Voucher đã lưu.

**Form sửa người dùng:** Gồm Section "Thông tin tài khoản" (các trường username và email bị khóa khi edit — `disabled`, trường mật khẩu ẩn khi edit, trạng thái, vai trò — multi-select) và Section "Thông tin cá nhân" (họ tên, SĐT, giới tính, ngày sinh).

### 5.8.2. Quản Lý Vai Trò (RoleResource)

Quản lý vai trò hệ thống thông qua Spatie Laravel Permission. Mỗi vai trò có tên và danh sách quyền hạn (permissions) được gán. Hệ thống phân quyền ảnh hưởng trực tiếp đến khả năng truy cập từng Resource thông qua trait `HasResourcePermission` — mỗi Resource khai báo một `$requiredPermission` và kiểm tra quyền trước khi hiển thị mục điều hướng.

### 5.8.3. Quản Lý Đối Tác (PartnerResource)

Quản lý tất cả đối tác bao gồm cả nhà cung cấp (suppliers) và nhà vận chuyển (shipping_providers). Trường `type` phân biệt loại đối tác, trường `is_active` điều khiển khả dụng trong các dropdown của hệ thống.

### 5.8.4. Nhật Ký Hoạt Động (ActivityLog, OrderActivity, SystemActivity)

Hệ thống logging được tổ chức thành 3 sub-resource:

- **ActivityLog:** Nhật ký tổng thể — ghi lại mọi hành động quan trọng trong hệ thống thông qua `ActivityLogService`. Hỗ trợ polymorphic (gắn với Order, GoodsReceipt, v.v.). Tự động xóa log cũ hơn 90 ngày (`Prunable`).
- **OrderActivityResource:** Bộ lọc chuyên biệt chỉ hiển thị nhật ký liên quan đến đơn hàng.
- **SystemActivityResource:** Bộ lọc chỉ hiển thị nhật ký liên quan đến thao tác hệ thống (nhập kho, cấu hình, v.v.).

Mỗi bản ghi log bao gồm: loại hành động, đối tượng liên quan, người thực hiện, dữ liệu thay đổi (before/after) và thời gian.

---

## 5.9. Mẫu Giao Diện Chuẩn Filament (Pattern Language)

Mọi Resource trong hệ thống đều tuân theo các mẫu giao diện nhất quán của Filament v3:

**Trang danh sách (List Page):** Bảng dữ liệu đầy đủ tính năng: tìm kiếm toàn cục, sắp xếp theo cột, bộ lọc dạng dropdown/drawer, phân trang (10/25/50/100 dòng mỗi trang), toggle hiển thị/ẩn cột, bulk actions và row actions dạng icon hoặc nhóm action.

**Trang tạo/sửa (Create/Edit Page):** Form đặt trong layout với breadcrumb, tiêu đề hành động và nút Save. Các component form phong phú: TextInput, Select (có thể tìm kiếm, preload, tạo nhanh), Textarea, Toggle, FileUpload (tích hợp image editor), DateTimePicker, Repeater (danh sách động), Section (nhóm trường), Grid (bố cục cột), Hidden (trường ẩn dữ liệu).

**Trang xem chi tiết (View/Infolist Page):** Hiển thị thông tin read-only với Infolist components: TextEntry (có thể copy, badge, link, màu ngữ nghĩa), ImageEntry, Section, Grid và RelationManager dạng tab.

**Thông báo (Notifications):** Mọi hành động thành công hoặc thất bại đều phát ra Filament Notification (toast popup góc phải màn hình) với màu sắc phân biệt: xanh lá (success), đỏ (danger), vàng (warning), xanh dương (info).

**Xác nhận nguy hiểm (Confirmation Modal):** Các hành động không thể hoàn tác (hủy đơn, xóa, khóa tài khoản) đều yêu cầu xác nhận qua modal với mô tả hành động và hai nút Confirm/Cancel.

---

## 5.10. Hệ Thống Phân Quyền Admin (RBAC)

Hệ thống quản trị áp dụng kiểm soát truy cập dựa trên vai trò thông qua trait `HasResourcePermission`. Mỗi Filament Resource khai báo thuộc tính `$requiredPermission`, và phương thức `canViewAny()` được override để kiểm tra xem người dùng hiện tại có quyền tương ứng hay không — nếu không có quyền, mục điều hướng sẽ tự động ẩn khỏi sidebar và URL trực tiếp trả về 403.

Một số permission tiêu biểu trong hệ thống:

| Permission | Resource áp dụng |
|---|---|
| `view_products` | ProductResource, CategoryResource, BrandResource |
| `view_orders` | OrderResource |
| `view_vouchers` | VoucherResource |
| `manage_goods_receipt` | GoodsReceiptResource |
| `manage_inventory` | InventoryResource |
| `view_users` | UserResource |
| `manage_users` | Các action Lock/Unlock, Bulk activate/ban |
| `view_reports` | Tất cả Dashboard Widgets |

Điều này cho phép phân tách chức năng giữa các vai trò như: **Kho hàng** (chỉ truy cập Logistics), **Sale** (chỉ truy cập Orders và Vouchers), **Content Editor** (chỉ truy cập Blog và Banner) và **Super Admin** (toàn quyền).

---

## 5.11. Nhận Xét Tổng Quan về Giao Diện Quản Trị

Giao diện admin của hệ thống TheGioiDiDong Clone thể hiện các ưu điểm nổi bật của việc sử dụng Filament v3: **tốc độ phát triển cao** nhờ hệ thống component phong phú sẵn có, **tính nhất quán tuyệt đối** trong UX/UI giữa tất cả các trang quản trị, và **khả năng tùy biến sâu** thông qua PHP code thuần túy mà không cần viết HTML/CSS.

Đặc biệt, hệ thống đã vượt ra ngoài khuôn khổ CRUD đơn giản để xây dựng các luồng nghiệp vụ phức tạp trực tiếp trên giao diện Filament: quy trình xử lý đơn hàng từng bước (confirm → ship → deliver), tạo đơn thủ công với tính toán tổng tiền live, nhập kho nhiều sản phẩm qua Repeater, và Dashboard analytics đa chiều. Sự kết hợp giữa Livewire reactivity và Alpine.js đảm bảo mọi tương tác trên admin panel đều mượt mà, không cần reload trang, mang lại trải nghiệm làm việc tiệm cận với các SaaS platform hiện đại.
