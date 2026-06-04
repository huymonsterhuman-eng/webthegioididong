# IV. MÔ TẢ GIAO DIỆN NGƯỜI DÙNG (USER INTERFACE)

Giao diện người dùng của hệ thống website bán lẻ điện thoại TheGioiDiDong Clone được thiết kế theo phong cách tối giản, hiện đại, lấy cảm hứng trực tiếp từ giao diện của thegioididong.com – một trong những nền tảng thương mại điện tử hàng đầu tại Việt Nam. Hệ thống sử dụng bộ màu đặc trưng gồm vàng (#FED700), xanh dương (#288AD6) và trắng làm nền chủ đạo, kết hợp với framework Tailwind CSS và thư viện Alpine.js nhằm tạo ra trải nghiệm người dùng mượt mà, đáp ứng tốt trên mọi kích thước màn hình (responsive design). Toàn bộ giao diện phía người dùng được tổ chức thành các khu vực chức năng rõ ràng, bao gồm: thanh điều hướng (header), trang chủ, trang danh mục sản phẩm, trang chi tiết sản phẩm, giỏ hàng, trang thanh toán, trang tìm kiếm, trang blog và khu vực tài khoản cá nhân.

---

## 4.1. Thanh Điều Hướng (Header)

Thanh điều hướng (header) là thành phần giao diện cố định (sticky) ở đầu trang, luôn hiển thị khi người dùng cuộn trang, đảm bảo khả năng tiếp cận các tính năng cốt lõi bất kỳ lúc nào. Thanh header được chia thành hai tầng chức năng phân biệt:

**Tầng trên (Primary Navigation Bar):** Được tô màu vàng đặc trưng (#FED700), tầng này bao gồm logo thương hiệu ở góc trái, thanh tìm kiếm toàn trang ở giữa (hiển thị trên màn hình desktop), và nhóm các nút hành động ở góc phải. Nhóm hành động bao gồm: đường dây nóng tổng đài miễn phí (1800.1060), nút đăng nhập/thông tin tài khoản người dùng với dropdown menu tích hợp (cho phép nhanh chóng truy cập **Tài khoản của tôi**, **Đơn mua**, **Kho Voucher** và **Đăng xuất**), và nút Giỏ hàng kèm badge đếm số lượng sản phẩm hiện tại. Trên thiết bị di động, thanh tìm kiếm được ẩn đi, thay vào đó xuất hiện nút hamburger (☰) mở menu trượt từ trái sang (slide-out drawer).

**Tầng dưới (Category Navigation Bar):** Được tô màu tối (#333333), tầng này liệt kê tối đa 8 bộ sưu tập sản phẩm chính (ví dụ: Điện thoại, Laptop, Tablet, Smartwatch, v.v.) theo dạng menu ngang. Mỗi mục hỗ trợ mega dropdown khi hover chuột, cho phép điều hướng nhanh đến các danh mục con. Tầng category chỉ hiển thị trên màn hình desktop; trên mobile, toàn bộ danh mục được tích hợp vào menu trượt.

Phần **chân trang (footer)** cung cấp các liên kết tĩnh về chính sách bảo hành, thông tin công ty, tuyển dụng, đường dây tổng đài hỗ trợ (gọi mua: 1800.1060; kỹ thuật: 1800.1763; khiếu nại: 1800.1062; bảo hành: 1800.1064) và kênh mạng xã hội (Facebook, YouTube), phân bổ theo bố cục 4 cột trên màn hình lớn và 1 cột trên thiết bị di động.

---

## 4.2. Trang Chủ (Home Page)

Trang chủ là điểm tiếp xúc đầu tiên giữa người dùng và hệ thống, được thiết kế nhằm tối ưu hóa nhận thức thương hiệu và dẫn dắt hành vi mua hàng. Giao diện trang chủ được cấu thành từ các khối nội dung xếp dọc theo thứ tự như sau:

**(1) Hero Slider (Banner quảng cáo):** Là khu vực nổi bật nhất ngay dưới header, hiển thị banner quảng cáo dạng carousel với hiệu ứng chuyển động mượt mà. Các banner được quản lý động từ hệ thống admin, mỗi banner có thể gắn kèm liên kết đích để dẫn hướng người dùng đến trang khuyến mãi hoặc sản phẩm cụ thể.

**(2) Khu vực Voucher — "Siêu hội Voucher":** Hiển thị có điều kiện, chỉ xuất hiện khi hệ thống có voucher đang hoạt động. Mỗi voucher được trình bày dưới dạng thẻ phiếu (coupon card) chia hai phần: phần trái màu xanh thể hiện giá trị giảm (theo phần trăm hoặc số tiền cố định), phần phải thể hiện mã code, điều kiện áp dụng và hạn sử dụng. Người dùng có thể nhấn nút **"Lưu ngay"** để lưu voucher vào tài khoản cá nhân thông qua API bất đồng bộ (AJAX), không cần tải lại trang. Trên màn hình desktop, các voucher hiển thị theo lưới 4 cột; trên mobile thu gọn còn 1 cột.

**(3) Khu vực Sản phẩm Nổi bật:** Được đánh dấu bằng tiêu đề có biểu tượng ngọn lửa đỏ cùng dòng chữ "Sản phẩm nổi bật", khu vực này hiển thị lưới sản phẩm gồm 2 cột trên mobile và 5 cột trên desktop. Mỗi sản phẩm được trình bày qua thẻ sản phẩm (product card) thống nhất về thiết kế trong toàn bộ hệ thống.

**(4) Các Bộ Sưu Tập (Collection Grids):** Tiếp nối phần sản phẩm nổi bật, hệ thống tự động render từng bộ sưu tập đang hoạt động có trạng thái "hiển thị trên trang chủ". Mỗi bộ sưu tập có tiêu đề riêng, lưới sản phẩm 5 cột và liên kết **"Xem tất cả"** dẫn đến trang bộ sưu tập tương ứng. Các bộ sưu tập được xếp lần lượt theo thứ tự ưu tiên do quản trị viên cấu hình.

**(5) Khu vực Tin Công Nghệ (Blog Section):** Phần cuối trang chủ hiển thị 3 bài viết blog mới nhất dưới dạng card có ảnh thumbnail, badge danh mục, ngày đăng, tiêu đề và đoạn trích nội dung. Có nút **"Xem tất cả"** dẫn đến trang blog đầy đủ. Ảnh thumbnail có hiệu ứng zoom nhẹ khi hover, tạo phản hồi thị giác tích cực cho người dùng.

---

## 4.3. Trang Danh Mục Sản Phẩm (Category Page)

Trang danh mục sản phẩm hiển thị danh sách toàn bộ sản phẩm thuộc một danh mục cụ thể, cung cấp trải nghiệm duyệt sản phẩm có cấu trúc. Bố cục trang bao gồm các thành phần sau:

**Breadcrumb điều hướng:** Nằm ở đầu trang, thể hiện đường dẫn phân cấp từ Trang chủ (biểu tượng ngôi nhà) đến danh mục hiện tại, giúp người dùng xác định vị trí trong cấu trúc website và quay trở lại trang trước dễ dàng.

**Tiêu đề và bộ đếm:** Tên danh mục được hiển thị dạng chữ hoa, đậm, kèm theo tổng số lượng sản phẩm tìm thấy ở góc phải.

**Thanh lọc và sắp xếp (Filter Bar):** Thanh lọc theo hãng sản xuất hiển thị dưới dạng các nút tag tròn (pill buttons), nút đang được chọn được highlight màu xanh dương với nền xanh nhạt. Nút "Tất cả" luôn hiển thị đầu tiên cho phép xóa bộ lọc hãng. Dropdown sắp xếp cung cấp 3 tùy chọn: Mới nhất (mặc định), Giá từ thấp đến cao và Giá từ cao đến thấp. Cả hai bộ lọc hoạt động qua cơ chế truy vấn URL (query string), đảm bảo có thể bookmark và chia sẻ kết quả lọc.

**Lưới sản phẩm (Product Grid):** Hiển thị theo dạng lưới 2 cột trên mobile, 3 cột trên tablet và 5 cột trên desktop. Cuối trang có phân trang (pagination) dạng liên kết, hỗ trợ điều hướng khi danh sách sản phẩm vượt quá ngưỡng hiển thị mặc định. Khi không có sản phẩm phù hợp, hệ thống hiển thị thông báo trống kèm biểu tượng hộp mở và dòng chữ "Không tìm thấy sản phẩm nào phù hợp."

---

## 4.4. Thẻ Sản Phẩm (Product Card Component)

Thẻ sản phẩm là component tái sử dụng (Blade component) xuất hiện đồng nhất trên trang chủ, trang danh mục, trang bộ sưu tập và trang tìm kiếm. Mỗi thẻ bao gồm:

- **Ảnh sản phẩm** có hiệu ứng zoom khi hover, dẫn đến trang chi tiết.
- **Tên sản phẩm** giới hạn 2 dòng (line-clamp), tránh làm vỡ bố cục lưới.
- **Giá bán:** Nếu có khuyến mãi, hiển thị giá sale màu đỏ và giá gốc bị gạch ngang ở dưới. Nếu không có sale, hiển thị một mức giá duy nhất.
- **Nút "Mua ngay"** xuất hiện khi hover, gọi hàm Alpine.js `addToCart()` để thêm sản phẩm vào giỏ hàng mà không cần rời trang.

---

## 4.5. Trang Chi Tiết Sản Phẩm (Product Detail Page)

Đây là giao diện trọng tâm trong quy trình mua hàng, được thiết kế theo bố cục 2 cột (12-cột grid) trên màn hình lớn, chuyển sang 1 cột trên màn hình nhỏ:

**Cột trái (7/12 cột):**

- **Ảnh sản phẩm:** Hiển thị căn giữa trên nền trắng, kích thước vùng chứa tối thiểu 400px chiều cao, hỗ trợ ảnh chính (primary image) ưu tiên hơn ảnh mặc định. Khi không có ảnh, hiển thị biểu tượng điện thoại placeholder.
- **Mô tả sản phẩm:** Khối "Đặc điểm nổi bật" trình bày nội dung mô tả chi tiết với định dạng xuống dòng tự nhiên. Nếu chưa có nội dung, hiển thị thông báo placeholder nghiêng chữ.

**Cột phải (5/12 cột):**

- **Khối giá bán:** Giá khuyến mãi hiển thị màu đỏ đậm cỡ 3xl kèm badge phần trăm giảm (ví dụ: -15%), giá gốc bị gạch ngang bên dưới. Nếu không có sale, chỉ hiển thị một mức giá. Badge tình trạng tồn kho: "Còn X sản phẩm" (xanh lá) hoặc "Hết hàng" (đỏ). Danh sách ưu đãi tóm tắt trong khung viền xanh nét đứt.
- **Nút "Mua ngay":** Màu đỏ (#DC2626), chiều rộng toàn bộ, có dòng chữ phụ "Giao hàng miễn phí hoặc nhận tại shop". Khi sản phẩm hết hàng, nút chuyển sang màu xám và bị vô hiệu hóa (disabled).
- **Khối thông số kỹ thuật:** Bảng tóm tắt cấu hình gồm các hàng xen kẽ màu nền (striped rows) với các thông số: Màn hình, Hệ điều hành, Camera, Chip xử lý và Pin.

**Khu vực Đánh giá sản phẩm (Review Section):**

Nằm cuối trang, phân tách bởi đường kẻ ngang, chia thành 2 cột:

- **Cột trái (4/12):** Điểm trung bình dạng số lớn (font black, màu brand-blue), hệ thống 5 sao trực quan và tổng số lượt đánh giá. Phần dưới là form gửi đánh giá dành cho người dùng đã đăng nhập và chưa từng đánh giá sản phẩm đó — gồm chọn số sao tương tác (hover highlight), ô nhập nhận xét và tùy chọn đính kèm ảnh. Nếu đã đánh giá, hiển thị thông báo xác nhận. Nếu chưa đăng nhập, hiển thị nút dẫn đến trang đăng nhập.
- **Cột phải (8/12):** Danh sách đánh giá đã được duyệt, mỗi mục gồm avatar chữ cái đầu tên người dùng, tên, thời gian tương đối, số sao, nội dung nhận xét, ảnh đính kèm (có thể phóng to khi nhấn) và phần phản hồi của quản trị viên (nếu có, hiển thị trong khung nền xám với đường viền xanh bên trái).

**Sản phẩm tương tự:** Lưới sản phẩm cùng danh mục ở cuối trang (2-5 cột tùy màn hình) khuyến khích khám phá thêm.

---

## 4.6. Giỏ Hàng Trượt (Cart Flyout)

Giỏ hàng được thiết kế theo dạng panel trượt từ phải sang (slide-over panel) với độ rộng tối đa 448px, hoạt động như một lớp phủ (overlay) có nền mờ bên ngoài — giúp duy trì ngữ cảnh mua sắm liên tục mà không chuyển hướng người dùng sang trang mới. Dữ liệu giỏ hàng được lưu trong `localStorage` của trình duyệt, đảm bảo tồn tại giữa các phiên làm việc mà không phụ thuộc trạng thái đăng nhập.

Giao diện giỏ hàng bao gồm các thành phần:

- **Tiêu đề** kèm số lượng sản phẩm hiện có trong giỏ và nút đóng (×).
- **Thông báo cảnh báo tồn kho** (nếu số lượng yêu cầu vượt quá tồn kho) hiển thị trong dải đỏ với tự động ẩn sau 5 giây.
- **Danh sách sản phẩm** có thể cuộn, mỗi mục gồm: ảnh thu nhỏ 80×80px, tên sản phẩm (tối đa 2 dòng), đơn giá màu đỏ, số lượng còn trong kho, và **nhóm điều chỉnh số lượng** (nút trừ [−], ô nhập số trực tiếp và nút cộng [+]) cùng nút xóa sản phẩm. Hệ thống tự động kiểm tra và giới hạn số lượng không vượt tồn kho.
- **Footer cố định** (khi giỏ có hàng): tổng tiền định dạng VND và nút **"ĐẶT HÀNG"** màu đỏ dẫn đến trang thanh toán.
- **Trạng thái trống:** Khi giỏ không có sản phẩm, hiển thị biểu tượng giỏ hàng mờ và nút "Tiếp tục mua sắm".

Mỗi khi số lượng sản phẩm trong giỏ thay đổi, badge đếm trên nút giỏ hàng ở header cập nhật theo thời gian thực thông qua Alpine.js reactive data binding.

---

## 4.7. Trang Thanh Toán — Xác Nhận Đơn Hàng (Checkout Page)

Trang thanh toán yêu cầu người dùng đăng nhập (protected route) và sử dụng bố cục 2 cột cân bằng trên màn hình lớn: **cột trái** là form điền thông tin, **cột phải** là bảng tóm tắt đơn hàng.

**Thông tin giao hàng:**

Nếu người dùng đã lưu địa chỉ trong sổ địa chỉ, hệ thống cung cấp hai lựa chọn thông qua radio button: "Chọn địa chỉ đã lưu" hoặc "Nhập địa chỉ mới". Khi chọn địa chỉ đã lưu, danh sách hiển thị từng địa chỉ dạng card có viền, bao gồm tên người nhận, số điện thoại và địa chỉ đầy đủ; địa chỉ mặc định được đánh dấu badge "Mặc định" màu xanh. Khi chọn nhập địa chỉ mới, form yêu cầu điền Họ tên, Số điện thoại, Địa chỉ giao hàng và có tùy chọn "Lưu địa chỉ này vào sổ địa chỉ" cho lần sau.

**Phương thức vận chuyển:**

Hai lựa chọn được trình bày dạng radio card có viền (border highlight khi được chọn):
- **Giao hàng tiêu chuẩn:** Nhận hàng trong 2-3 ngày, phí 30.000₫.
- **Giao hàng hỏa tốc 2h:** Chỉ áp dụng nội thành các thành phố lớn, phí 50.000₫.

Phí vận chuyển cập nhật tức thì trên bảng tóm tắt khi người dùng thay đổi lựa chọn.

**Phương thức thanh toán:**

Ba phương thức cung cấp dưới dạng radio card có logo nhận diện trực quan:
- **COD — Thanh toán khi nhận hàng:** Icon chuyển tiền nền xanh.
- **VNPay:** Logo VNPay, hỗ trợ thẻ ATM và Internet Banking.
- **MoMo:** Logo MoMo nền tím, ví điện tử.

**Bảng tóm tắt đơn hàng (Order Summary):**

Cột phải liệt kê từng sản phẩm trong giỏ (ảnh, tên, số lượng, đơn giá) trong vùng cuộn tối đa 384px. Bên dưới là:
- Ô nhập **mã voucher** kèm nút "Áp dụng". Nếu người dùng có voucher đã lưu phù hợp, hệ thống gợi ý sẵn với nút "Dùng" để áp dụng một chạm.
- Thông báo kết quả áp dụng voucher: màu xanh lá (thành công) hoặc đỏ (lỗi).
- Bảng tổng kết: Tạm tính → Phí vận chuyển → Giảm giá (nếu có) → **Tổng cộng** màu đỏ.

**Nút "Hoàn tất đặt hàng":** Màu vàng đặc trưng (#FED700), chiều rộng toàn bộ, kích thước lớn với hiệu ứng hover 3D (dịch chuyển xuống, viền dưới đổi màu) tạo cảm giác nhấn vật lý. Kèm dòng chú thích nhỏ nhắc nhở kiểm tra lại đơn hàng.

---

## 4.8. Trang Kết Quả Tìm Kiếm (Search Page)

Trang tìm kiếm hiển thị danh sách sản phẩm phù hợp với từ khóa do người dùng nhập vào thanh tìm kiếm trên header. Tìm kiếm hoạt động theo cơ chế GET request với tham số `q`, đảm bảo URL có thể bookmark và chia sẻ.

Giao diện bao gồm: breadcrumb (Trang chủ → Tìm kiếm: [từ khóa]), tiêu đề kết quả hiển thị từ khóa in nghiêng kèm tổng số sản phẩm tìm được, và lưới sản phẩm (2 cột trên mobile, lên đến 5 cột trên desktop) với phân trang cuối trang. Khi không có kết quả, hệ thống hiển thị biểu tượng kính lúp mờ, thông báo "Không tìm thấy kết quả nào" kèm nút **"Tiếp tục mua sắm"** dẫn về trang chủ.

---

## 4.9. Trang Blog Tin Công Nghệ (Blog Page & Post Detail)

Module blog bao gồm hai giao diện chính:

**Trang danh sách bài viết (Blog Index):**

Hiển thị tiêu đề "Tin công nghệ" dạng chữ hoa cùng mô tả ngắn, tiếp theo là lưới 3 cột các card bài viết (responsive về 1 cột trên mobile, 2 cột trên tablet). Mỗi card gồm: ảnh thumbnail chiều cao cố định 224px có hiệu ứng zoom khi hover, badge danh mục, ngày đăng, tiêu đề (tối đa 2 dòng), đoạn trích 3 dòng và liên kết **"Xem chi tiết"** với mũi tên. Phân trang được tích hợp ở cuối danh sách.

**Trang chi tiết bài viết (Post Detail):**

Bố cục tập trung với chiều rộng tối đa 896px, căn giữa trang, đặt trong khung card bo góc có đổ bóng nhẹ. Cấu trúc bài gồm:
- Breadcrumb (Trang chủ → Tin công nghệ → Tiêu đề bài).
- Badge danh mục màu xanh dương + ngày và giờ đăng.
- Tiêu đề lớn (H1, font extrabold).
- Đoạn dẫn (excerpt) in nghiêng với đường viền vàng bên trái (border-left accent).
- Ảnh bìa chiều rộng đầy đủ, chiều cao tối đa 500px, `object-cover`.
- Nội dung bài viết đầy đủ, định dạng xuống dòng tự nhiên.
- Nhóm nút chia sẻ tròn ở cuối bài: Facebook (xanh dương), Twitter (xanh nhạt) và Sao chép liên kết (đen).

---

## 4.10. Khu Vực Tài Khoản Cá Nhân (Account Area)

Khu vực tài khoản chỉ dành cho người dùng đã xác thực, sử dụng layout riêng biệt (`layouts/account`) với thanh điều hướng dọc cố định bên trái liệt kê các mục: Tổng quan, Thông tin cá nhân, Sổ địa chỉ, Đơn hàng của tôi, Kho Voucher và Bảo mật. Phần nội dung chính chiếm không gian còn lại bên phải theo dạng bố cục 2 cột không cân xứng.

**Dashboard Tài khoản (Account Overview):**

Hiển thị banner chào mừng cá nhân hóa theo tên người dùng với đường viền vàng bên trái, tiếp theo là 3 ô thống kê nhanh (KPI cards) thể hiện trực quan: tổng số đơn hàng (icon hộp mở, nền xanh nhạt), số voucher còn khả dụng (icon vé, nền vàng nhạt) và số lượt đánh giá sản phẩm (icon sao, nền xanh lá nhạt). Phần cuối là bảng đơn hàng gần đây với 5 cột: Mã đơn hàng (liên kết xem chi tiết), Ngày đặt, Sản phẩm (tóm tắt tên), Tổng tiền màu đỏ và Trạng thái với badge màu phân biệt.

**Quản lý Đơn hàng (My Orders):**

Bảng danh sách toàn bộ đơn hàng có phân trang. Mỗi đơn hàng được định danh theo định dạng `ORD-YYYYMMDD-XXX` (ví dụ: ORD-20250503-001). Trạng thái đơn hàng được biểu diễn bằng badge màu theo quy ước ngữ nghĩa:

| Trạng thái | Màu Badge |
|---|---|
| Chờ xử lý | Vàng nhạt |
| Đã xác nhận | Xanh dương nhạt |
| Đang giao hàng | Cam nhạt |
| Đã giao thành công | Xanh lá nhạt |
| Đã hủy | Đỏ nhạt |

Người dùng có thể vào trang chi tiết từng đơn để xem danh sách sản phẩm, địa chỉ giao hàng, phương thức thanh toán và thực hiện hủy đơn (chỉ áp dụng với đơn đang ở trạng thái cho phép hủy).

**Sổ Địa Chỉ (Address Book):**

Quản lý nhiều địa chỉ giao hàng. Mỗi địa chỉ có đầy đủ chức năng: xem, chỉnh sửa, xóa và thiết lập làm mặc định. Địa chỉ mặc định được đánh dấu nổi bật và tự động được ưu tiên điền sẵn tại trang thanh toán.

**Kho Voucher (My Vouchers):**

Liệt kê các voucher đã lưu từ trang chủ, hiển thị mã code, giá trị giảm, điều kiện áp dụng (đơn tối thiểu), giá trị giảm tối đa (nếu là loại phần trăm) và hạn sử dụng. Voucher đã sử dụng được phân loại riêng.

**Đổi mật khẩu (Security):**

Form bảo mật yêu cầu nhập mật khẩu hiện tại, mật khẩu mới và xác nhận mật khẩu mới. Validation được thực hiện phía server với thông báo lỗi hiển thị nội tuyến bên dưới từng trường.

---

## 4.11. Trang Xác Thực Người Dùng (Authentication Pages)

Các trang xác thực — Đăng nhập, Đăng ký, Quên mật khẩu, Đặt lại mật khẩu, Xác minh email — sử dụng layout khách (`layouts/guest`) riêng biệt với bố cục đơn giản, tập trung vào form và không có thanh điều hướng sản phẩm. Hệ thống được xây dựng trên **Laravel Breeze**, cung cấp đầy đủ luồng xác thực hiện đại.

Giao diện form xác thực sử dụng các component tái sử dụng (`x-text-input`, `x-input-label`, `x-primary-button`) để đảm bảo tính nhất quán về kiểu dáng. Thông báo lỗi validation và thông báo trạng thái session được hiển thị nội tuyến (inline) ngay bên dưới trường tương ứng — không sử dụng modal hay popup, tối ưu trải nghiệm người dùng theo nguyên tắc inline validation.

---

## 4.12. Nhận Xét Tổng Quan về Giao Diện Người Dùng

Nhìn chung, giao diện người dùng của hệ thống TheGioiDiDong Clone thể hiện sự nhất quán cao trong ngôn ngữ thiết kế (design language) xuyên suốt toàn bộ các trang. Bộ màu thương hiệu, typography, khoảng cách và các component tái sử dụng qua Blade components đã tạo ra trải nghiệm thị giác đồng bộ.

Hệ thống tối ưu hiệu năng qua nhiều kỹ thuật: lazy loading hình ảnh giảm thời gian tải trang ban đầu; lưu trữ giỏ hàng phía client (localStorage) giúp trải nghiệm mua sắm không bị gián đoạn; Alpine.js reactive data binding cập nhật giao diện bất đồng bộ mà không cần tải lại trang; và kiến trúc Blade component đảm bảo code tái sử dụng, dễ bảo trì.

Thiết kế responsive đảm bảo trải nghiệm sử dụng mượt mà trên cả ba môi trường: desktop (≥1024px), tablet (768px–1023px) và điện thoại di động (<768px). Tất cả các thành phần giao diện tuân thủ nguyên tắc thiết kế lấy người dùng làm trung tâm (User-Centered Design), với luồng điều hướng trực quan, phản hồi thị giác rõ ràng cho mọi tương tác (hover states, disabled states, loading indicators) và thông báo trạng thái kịp thời giúp người dùng luôn nắm được kết quả của các hành động thực hiện.
