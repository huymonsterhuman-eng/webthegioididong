# Bảng Test Case — Dự án TheGioiDiDong Clone

---

## Nhóm A — Xác thực & Tài khoản

### A4 — Quên mật khẩu / Đặt lại mật khẩu

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Gửi yêu cầu đặt lại mật khẩu với email hợp lệ | Email đã tồn tại trong hệ thống | Hệ thống gửi email chứa liên kết đặt lại mật khẩu; hiển thị thông báo "Chúng tôi đã gửi liên kết đặt lại mật khẩu đến email của bạn" | |
| 2 | Gửi yêu cầu với email không tồn tại | Email chưa đăng ký | Hệ thống vẫn hiển thị thông báo thành công (tránh lộ thông tin tài khoản) hoặc thông báo lỗi rõ ràng | |
| 3 | Gửi yêu cầu với email sai định dạng | `abc@` hoặc `khongemail` | Hiển thị lỗi xác thực "Địa chỉ email không hợp lệ"; không gửi yêu cầu | |
| 4 | Đặt lại mật khẩu với liên kết hợp lệ | Token hợp lệ, mật khẩu mới `Abc@12345`, xác nhận khớp | Mật khẩu được cập nhật; người dùng được chuyển hướng đến trang đăng nhập; đăng nhập thành công với mật khẩu mới | |
| 5 | Đặt lại mật khẩu với liên kết đã hết hạn | Token hết hạn (> 60 phút) | Hiển thị thông báo lỗi "Liên kết đặt lại mật khẩu đã hết hạn"; yêu cầu gửi lại | |
| 6 | Đặt lại mật khẩu nhưng xác nhận không khớp | Mật khẩu mới `Abc@12345`, xác nhận `Abc@99999` | Hiển thị lỗi "Xác nhận mật khẩu không khớp"; không cập nhật mật khẩu | |

---

### A5 — Xác minh email

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xác minh email với liên kết hợp lệ | Nhấp liên kết xác minh trong email (token hợp lệ, chưa dùng) | Trường `email_verified_at` được cập nhật; người dùng được chuyển hướng vào hệ thống với thông báo "Email của bạn đã được xác minh" | |
| 2 | Xác minh email với liên kết đã dùng | Nhấp lại liên kết xác minh đã sử dụng trước đó | Hệ thống nhận biết email đã được xác minh; chuyển hướng hoặc thông báo phù hợp mà không báo lỗi | |
| 3 | Xác minh email với liên kết hết hạn / sai | Token bị thay đổi hoặc hết hạn | Hiển thị thông báo lỗi "Liên kết xác minh không hợp lệ hoặc đã hết hạn"; cung cấp tuỳ chọn gửi lại email xác minh | |
| 4 | Gửi lại email xác minh | Người dùng đã đăng nhập nhưng chưa xác minh email, nhấn "Gửi lại email xác minh" | Hệ thống gửi lại email xác minh mới; hiển thị thông báo "Email xác minh đã được gửi lại" | |

---

### A6 — Cập nhật thông tin cá nhân

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Cập nhật họ tên thành công | Tên mới hợp lệ: `Nguyễn Văn An` | Thông tin được lưu; hiển thị thông báo thành công; tên mới phản ánh ngay trên giao diện | |
| 2 | Cập nhật số điện thoại thành công | Số điện thoại hợp lệ: `0912345678` | Số điện thoại được lưu thành công | |
| 3 | Cập nhật với trường bắt buộc để trống | Xoá trắng trường Họ tên, nhấn Lưu | Hiển thị lỗi xác thực "Họ tên không được để trống"; không lưu thay đổi | |
| 4 | Cập nhật email trùng với tài khoản khác | Email đã được đăng ký bởi người dùng khác | Hiển thị lỗi "Email này đã được sử dụng"; không cập nhật | |
| 5 | Truy cập trang cập nhật khi chưa đăng nhập | Điều hướng trực tiếp đến `/account/profile` | Hệ thống chuyển hướng về trang đăng nhập | |

---

### A7 — Quản lý địa chỉ giao hàng

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Thêm địa chỉ mới hợp lệ | Họ tên, số điện thoại, địa chỉ đầy đủ, tỉnh/thành | Địa chỉ được lưu và hiển thị trong danh sách địa chỉ của người dùng | |
| 2 | Thêm địa chỉ với trường bắt buộc bỏ trống | Để trống trường "Địa chỉ cụ thể" | Hiển thị lỗi xác thực tương ứng; không lưu địa chỉ | |
| 3 | Chỉnh sửa địa chỉ đã có | Thay đổi số điện thoại liên lạc của địa chỉ hiện tại | Thông tin địa chỉ được cập nhật thành công; phản ánh ngay trong danh sách | |
| 4 | Đặt địa chỉ làm mặc định | Chọn "Đặt làm mặc định" cho một địa chỉ | Địa chỉ được đánh dấu mặc định; địa chỉ cũ mặc định bị huỷ đánh dấu; địa chỉ này tự động điền vào form checkout | |
| 5 | Xoá địa chỉ không phải mặc định | Nhấn Xoá trên địa chỉ không phải mặc định | Địa chỉ bị xoá khỏi danh sách; hiển thị thông báo xác nhận trước khi xoá | |
| 6 | Xoá địa chỉ mặc định duy nhất | Nhấn Xoá trên địa chỉ mặc định và duy nhất | Hệ thống từ chối hoặc cảnh báo; không cho phép xoá địa chỉ mặc định duy nhất để đảm bảo luồng checkout | |

---

### A8 — Đổi mật khẩu

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Đổi mật khẩu thành công | Mật khẩu hiện tại đúng, mật khẩu mới `NewPass@123`, xác nhận khớp | Mật khẩu được cập nhật; hiển thị thông báo thành công; phiên đăng nhập hiện tại vẫn duy trì | |
| 2 | Nhập sai mật khẩu hiện tại | Mật khẩu hiện tại sai, mật khẩu mới hợp lệ | Hiển thị lỗi "Mật khẩu hiện tại không chính xác"; không thực hiện thay đổi | |
| 3 | Mật khẩu mới và xác nhận không khớp | Mật khẩu mới `NewPass@123`, xác nhận `NewPass@456` | Hiển thị lỗi "Xác nhận mật khẩu không khớp"; không cập nhật | |
| 4 | Mật khẩu mới quá ngắn | Mật khẩu mới `123` (dưới 8 ký tự) | Hiển thị lỗi "Mật khẩu phải có ít nhất 8 ký tự"; không cập nhật | |
| 5 | Mật khẩu mới trùng mật khẩu cũ | Mật khẩu mới giống hệt mật khẩu hiện tại | Hệ thống cảnh báo "Mật khẩu mới không được trùng mật khẩu cũ" (nếu có rule này) hoặc chấp nhận tuỳ theo cấu hình | |
| 6 | Truy cập trang đổi mật khẩu khi chưa đăng nhập | Điều hướng trực tiếp đến `/account/password` | Hệ thống chuyển hướng về trang đăng nhập | |

---

## Nhóm B — Duyệt & Tìm kiếm sản phẩm

### B1 — Trang chủ

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Hiển thị banner trang chủ | Truy cập `/` | Các banner đang hoạt động hiển thị đúng thứ tự, ảnh tải đầy đủ | |
| 2 | Hiển thị bộ sưu tập nổi bật | Truy cập `/` | Các bộ sưu tập có `show_on_home = true` hiển thị trên trang chủ kèm sản phẩm thuộc bộ sưu tập | |
| 3 | Hiển thị sản phẩm nổi bật / bán chạy | Truy cập `/` | Danh sách sản phẩm nổi bật hiển thị đúng, có ảnh, tên, giá | |
| 4 | Hiển thị bài viết mới nhất | Truy cập `/` | Các bài viết blog mới nhất đã xuất bản hiển thị với tiêu đề, ảnh thumbnail và ngày đăng | |
| 5 | Trang chủ tải thành công khi chưa đăng nhập | Truy cập `/` khi chưa có phiên đăng nhập | Trang chủ hiển thị đầy đủ, không báo lỗi; header hiển thị nút Đăng nhập / Đăng ký | |

---

### B2 — Tìm kiếm sản phẩm

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tìm kiếm với từ khoá hợp lệ | Từ khoá: `iPhone` | Danh sách sản phẩm có tên chứa "iPhone" hiển thị; mỗi kết quả có ảnh, tên, giá | |
| 2 | Tìm kiếm với từ khoá không có kết quả | Từ khoá: `xyzkhongton` | Hiển thị thông báo "Không tìm thấy sản phẩm phù hợp"; không báo lỗi | |
| 3 | Tìm kiếm với ô nhập để trống | Nhấn tìm kiếm mà không nhập từ khoá | Hệ thống không thực hiện tìm kiếm hoặc hiển thị tất cả sản phẩm tuỳ cấu hình; không báo lỗi | |
| 4 | Tìm kiếm với từ khoá có ký tự đặc biệt | Từ khoá: `<script>alert(1)</script>` | Hệ thống xử lý an toàn, không thực thi script; hiển thị trang kết quả trống hoặc thông báo không tìm thấy | |
| 5 | Kết quả tìm kiếm dẫn đến đúng trang chi tiết | Nhấp vào một sản phẩm trong kết quả tìm kiếm | Chuyển hướng đến trang chi tiết sản phẩm đúng với URL dạng `/{categorySlug}/{productSlug}` | |

---

### B3 — Danh sách sản phẩm theo danh mục

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách sản phẩm theo danh mục hợp lệ | Truy cập URL danh mục có tồn tại | Danh sách sản phẩm thuộc danh mục đó hiển thị đúng, có phân trang nếu số lượng lớn | |
| 2 | Xem danh mục con (nested) | Truy cập danh mục có danh mục cha | Chỉ hiển thị sản phẩm thuộc danh mục con đó; breadcrumb hiển thị đúng cấp cha/con | |
| 3 | Truy cập danh mục không tồn tại | URL danh mục sai hoặc đã bị xoá | Hệ thống trả về trang 404 | |
| 4 | Danh mục không có sản phẩm nào | Truy cập danh mục rỗng | Hiển thị thông báo "Chưa có sản phẩm trong danh mục này"; không báo lỗi | |

---

### B4 — Danh sách sản phẩm theo bộ sưu tập

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem bộ sưu tập hợp lệ | Truy cập trang bộ sưu tập có tồn tại | Danh sách sản phẩm thuộc bộ sưu tập hiển thị đúng | |
| 2 | Xem bộ sưu tập con (nested) | Truy cập bộ sưu tập có cấp cha | Sản phẩm thuộc bộ sưu tập con hiển thị; cấu trúc cha/con phản ánh đúng | |
| 3 | Truy cập bộ sưu tập không tồn tại | URL bộ sưu tập sai | Hệ thống trả về trang 404 | |
| 4 | Bộ sưu tập không có sản phẩm | Truy cập bộ sưu tập rỗng | Hiển thị thông báo phù hợp; không báo lỗi | |

---

### B5 — Chi tiết sản phẩm

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem chi tiết sản phẩm hợp lệ | Truy cập `/{categorySlug}/{productSlug}` đúng | Hiển thị đầy đủ: tên, giá, ảnh gallery, thông số kỹ thuật, nút thêm vào giỏ | |
| 2 | Xem gallery ảnh sản phẩm | Nhấp vào từng ảnh trong gallery | Ảnh được phóng to hoặc chuyển đổi đúng; không có ảnh lỗi | |
| 3 | Xem đánh giá sản phẩm | Cuộn xuống phần đánh giá trên trang chi tiết | Danh sách đánh giá của người dùng hiển thị với tên, rating, nội dung; đánh giá bị ẩn (`is_hidden = true`) không hiển thị | |
| 4 | Truy cập sản phẩm không tồn tại | URL sản phẩm sai hoặc đã bị xoá | Hệ thống trả về trang 404 | |
| 5 | Truy cập sản phẩm đã bị soft delete | URL sản phẩm đã bị xoá mềm | Hệ thống trả về trang 404; không hiển thị thông tin sản phẩm đã xoá | |

---

## Nhóm C — Giỏ hàng & Mua sắm

### C1 — Thêm sản phẩm vào giỏ

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Thêm sản phẩm vào giỏ thành công | Người dùng đã đăng nhập, nhấn "Thêm vào giỏ" trên sản phẩm còn hàng | Sản phẩm xuất hiện trong giỏ hàng; số lượng badge trên icon giỏ tăng lên | |
| 2 | Thêm cùng sản phẩm nhiều lần | Nhấn "Thêm vào giỏ" nhiều lần với cùng một sản phẩm | Số lượng sản phẩm trong giỏ tăng tương ứng thay vì tạo dòng mới | |
| 3 | Thêm sản phẩm hết hàng | Nhấn "Thêm vào giỏ" trên sản phẩm có `stock = 0` | Nút bị vô hiệu hoá hoặc hiển thị thông báo "Sản phẩm tạm hết hàng"; không thêm vào giỏ | |
| 4 | Thêm sản phẩm khi chưa đăng nhập | Nhấn "Thêm vào giỏ" khi chưa có phiên đăng nhập | Hệ thống chuyển hướng đến trang đăng nhập hoặc yêu cầu đăng nhập trước | |

---

### C2 — Cập nhật số lượng trong giỏ

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tăng số lượng sản phẩm trong giỏ | Nhấn nút tăng số lượng hoặc nhập số lượng mới lớn hơn | Số lượng cập nhật đúng; tổng tiền trong giỏ tính lại chính xác | |
| 2 | Giảm số lượng về 1 | Giảm số lượng xuống còn 1 | Số lượng hiển thị là 1; không xoá sản phẩm khỏi giỏ | |
| 3 | Cập nhật số lượng vượt tồn kho | Nhập số lượng lớn hơn `stock` hiện có | Hệ thống thông báo "Số lượng vượt quá hàng tồn kho"; không cho phép cập nhật | |
| 4 | Nhập số lượng không hợp lệ | Nhập `0`, số âm hoặc ký tự chữ | Hệ thống từ chối giá trị; hiển thị lỗi xác thực hoặc tự động đặt lại về giá trị hợp lệ tối thiểu | |

---

### C3 — Xóa sản phẩm khỏi giỏ

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xoá một sản phẩm khỏi giỏ | Nhấn nút Xoá trên một dòng sản phẩm | Sản phẩm bị xoá khỏi danh sách; tổng tiền cập nhật lại; badge giỏ hàng giảm | |
| 2 | Xoá sản phẩm cuối cùng trong giỏ | Xoá sản phẩm duy nhất còn lại | Giỏ hàng trống; hiển thị thông báo "Giỏ hàng của bạn đang trống" và gợi ý tiếp tục mua sắm | |
| 3 | Xoá và kiểm tra tổng tiền | Xoá một sản phẩm trong giỏ có nhiều mặt hàng | Tổng tiền giảm đúng bằng đơn giá × số lượng sản phẩm vừa xoá | |

---

### C4 — Áp dụng mã voucher

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Áp dụng voucher hợp lệ theo phần trăm | Mã voucher giảm 10%, đơn hàng đạt điều kiện tối thiểu | Giảm giá được tính đúng (tối đa không vượt `max_discount`); tổng tiền cập nhật | |
| 2 | Áp dụng voucher hợp lệ theo số tiền cố định | Mã voucher giảm 50.000đ, đơn đạt điều kiện | Tổng tiền giảm đúng 50.000đ; hiển thị số tiền được giảm | |
| 3 | Áp dụng voucher khi đơn không đạt giá trị tối thiểu | Mã voucher yêu cầu đơn tối thiểu 500.000đ, đơn hiện tại 200.000đ | Hiển thị thông báo "Đơn hàng chưa đạt giá trị tối thiểu để dùng mã này"; không áp dụng | |
| 4 | Áp dụng voucher đã hết hạn | Mã voucher có `expired_at` trong quá khứ | Hiển thị thông báo "Mã giảm giá đã hết hạn"; không áp dụng | |
| 5 | Áp dụng voucher đã dùng hết lượt | Mã voucher đã đạt `max_uses` | Hiển thị thông báo "Mã giảm giá đã hết lượt sử dụng"; không áp dụng | |
| 6 | Áp dụng mã voucher không tồn tại | Nhập mã tuỳ ý không có trong hệ thống | Hiển thị thông báo "Mã giảm giá không hợp lệ"; không áp dụng | |
| 7 | Xoá voucher đã áp dụng | Nhấn huỷ / xoá mã đang áp dụng | Voucher bị gỡ; tổng tiền trả về giá trị gốc | |

---

### C5 — Checkout & Đặt hàng (COD)

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Đặt hàng thành công với COD | Giỏ hàng có sản phẩm, địa chỉ giao hàng hợp lệ, chọn COD | Đơn hàng được tạo với trạng thái `pending`; trang xác nhận đơn hàng hiển thị mã đơn; tồn kho chưa bị trừ | |
| 2 | Checkout khi giỏ hàng trống | Truy cập trang checkout với giỏ rỗng | Hệ thống chuyển hướng về giỏ hàng hoặc thông báo "Giỏ hàng trống"; không cho tạo đơn | |
| 3 | Checkout khi chưa có địa chỉ giao hàng | Người dùng chưa có địa chỉ nào được lưu | Hệ thống yêu cầu nhập hoặc thêm địa chỉ trước khi tiếp tục; không cho đặt hàng | |
| 4 | Checkout khi sản phẩm trong giỏ hết hàng | Sản phẩm trong giỏ trở nên hết hàng trước khi checkout | Hệ thống thông báo sản phẩm không còn đủ hàng; yêu cầu cập nhật giỏ trước khi tiếp tục | |
| 5 | Xác nhận thông tin trước khi đặt hàng | Xem trang xác nhận đơn hàng | Hiển thị đúng: danh sách sản phẩm, số lượng, đơn giá, địa chỉ giao hàng, phương thức thanh toán, tổng tiền | |

---


---

## Nhóm D — Đơn hàng & Voucher (User)

### D1 — Xem danh sách & chi tiết đơn hàng

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách đơn hàng của tài khoản | Người dùng đã đăng nhập, truy cập trang quản lý đơn hàng | Hiển thị đúng danh sách các đơn hàng thuộc tài khoản; mỗi dòng có mã đơn, ngày đặt, tổng tiền, trạng thái | |
| 2 | Xem chi tiết một đơn hàng | Nhấp vào một đơn hàng trong danh sách | Hiển thị đầy đủ: danh sách sản phẩm, số lượng, đơn giá, địa chỉ giao hàng, phương thức thanh toán, trạng thái hiện tại | |
| 3 | Tài khoản chưa có đơn hàng nào | Đăng nhập bằng tài khoản mới chưa từng đặt hàng | Hiển thị thông báo "Bạn chưa có đơn hàng nào"; không báo lỗi | |
| 4 | Truy cập chi tiết đơn hàng của người dùng khác | Thay đổi ID đơn hàng trong URL sang đơn của tài khoản khác | Hệ thống từ chối truy cập; trả về lỗi 403 hoặc 404 | |
| 5 | Truy cập trang đơn hàng khi chưa đăng nhập | Điều hướng trực tiếp đến `/account/orders` | Hệ thống chuyển hướng về trang đăng nhập | |

---

### D2 — Hủy đơn hàng

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Hủy đơn hàng ở trạng thái `pending` | Nhấn "Hủy đơn" trên đơn hàng đang chờ xác nhận | Đơn hàng chuyển sang trạng thái `cancelled`; tồn kho và voucher (nếu có) được hoàn trả; hiển thị thông báo hủy thành công | |
| 2 | Hủy đơn hàng ở trạng thái `confirmed` | Nhấn "Hủy đơn" trên đơn hàng đã xác nhận | Đơn hàng chuyển sang `cancelled`; các side effect hoàn kho, hoàn voucher được thực thi | |
| 3 | Hủy đơn hàng ở trạng thái `shipping` | Nhấn "Hủy đơn" trên đơn hàng đang giao | Hệ thống từ chối; hiển thị thông báo "Không thể hủy đơn hàng đang trong quá trình giao" | |
| 4 | Hủy đơn hàng đã giao thành công | Nhấn "Hủy đơn" trên đơn hàng có trạng thái `delivered` | Hệ thống từ chối; nút hủy không hiển thị hoặc bị vô hiệu hóa | |

---

### D3 — Xem danh sách voucher

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách voucher đã lưu | Người dùng đã đăng nhập, truy cập trang voucher | Hiển thị đúng các voucher đã lưu kèm thông tin: mã, mức giảm, điều kiện, hạn sử dụng, trạng thái | |
| 2 | Phân biệt voucher còn hạn và đã hết hạn | Tài khoản có cả voucher còn hạn và hết hạn | Hệ thống phân biệt rõ ràng hai nhóm; voucher hết hạn hiển thị khác biệt (mờ, gạch ngang hoặc nhãn "Hết hạn") | |
| 3 | Tài khoản chưa lưu voucher nào | Đăng nhập bằng tài khoản chưa lưu voucher | Hiển thị thông báo "Bạn chưa có voucher nào"; không báo lỗi | |

---

### D4 — Lưu voucher mới

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Lưu voucher hợp lệ chưa từng lưu | Nhấn "Lưu voucher" trên mã còn hiệu lực, còn lượt | Voucher được ghi vào bảng `user_voucher`; hiển thị thông báo lưu thành công; voucher xuất hiện trong danh sách của người dùng | |
| 2 | Lưu lại voucher đã lưu trước đó | Nhấn "Lưu voucher" trên mã đã tồn tại trong danh sách | Hệ thống thông báo "Bạn đã lưu voucher này rồi"; không tạo bản ghi trùng | |
| 3 | Lưu voucher đã hết hạn | Nhấn "Lưu voucher" trên mã hết hạn | Hệ thống từ chối; hiển thị thông báo "Voucher đã hết hạn" | |
| 4 | Lưu voucher khi chưa đăng nhập | Nhấn "Lưu voucher" khi chưa có phiên đăng nhập | Hệ thống chuyển hướng đến trang đăng nhập | |

---

## Nhóm E — Đánh giá & Blog

### E1 — Gửi đánh giá sản phẩm

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Gửi đánh giá hợp lệ | Người dùng đã đăng nhập, chọn rating 5 sao, nhập nhận xét, nhấn Gửi | Đánh giá được lưu; hiển thị thành công; rating trung bình của sản phẩm cập nhật | |
| 2 | Gửi đánh giá kèm ảnh | Chọn rating, nhập nhận xét, đính kèm ảnh hợp lệ | Đánh giá được lưu cùng ảnh; ảnh hiển thị trong phần đánh giá trên trang chi tiết sản phẩm | |
| 3 | Gửi đánh giá không chọn rating | Nhập nhận xét nhưng không chọn số sao | Hiển thị lỗi xác thực "Vui lòng chọn số sao"; không gửi đánh giá | |
| 4 | Gửi đánh giá khi chưa đăng nhập | Nhấn Gửi đánh giá khi chưa có phiên đăng nhập | Hệ thống chuyển hướng đến trang đăng nhập hoặc yêu cầu đăng nhập | |

---

### E2 — Giới hạn 1 đánh giá / sản phẩm

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Gửi đánh giá thứ hai cho cùng sản phẩm | Người dùng đã có đánh giá trên sản phẩm, cố gửi thêm | Hệ thống từ chối; hiển thị thông báo "Bạn đã đánh giá sản phẩm này rồi"; không tạo bản ghi trùng | |
| 2 | Form đánh giá ẩn với người đã đánh giá | Truy cập trang chi tiết sản phẩm đã từng đánh giá | Form gửi đánh giá không hiển thị hoặc bị vô hiệu hóa; thay vào đó hiển thị đánh giá đã gửi của người dùng | |
| 3 | Hai tài khoản khác nhau đánh giá cùng sản phẩm | Tài khoản A và tài khoản B cùng gửi đánh giá cho sản phẩm X | Cả hai đánh giá đều được lưu thành công; ràng buộc unique chỉ áp dụng trong phạm vi một tài khoản | |

---

### E3 — Xem danh sách bài viết

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách bài viết đã xuất bản | Truy cập trang blog | Hiển thị các bài viết có `published_at` nhỏ hơn hoặc bằng thời điểm hiện tại; mỗi bài có tiêu đề, ảnh thumbnail, tóm tắt, ngày đăng | |
| 2 | Bài viết chưa đến giờ đăng không hiển thị | Bài viết có `published_at` trong tương lai | Bài viết không xuất hiện trong danh sách công khai | |
| 3 | Phân trang danh sách bài viết | Số bài viết vượt quá giới hạn một trang | Phân trang hoạt động đúng; chuyển trang hiển thị đúng nhóm bài viết tiếp theo | |

---

### E4 — Xem chi tiết bài viết

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem chi tiết bài viết đã xuất bản | Nhấp vào bài viết trong danh sách | Hiển thị đầy đủ nội dung bài viết, tiêu đề, ảnh, ngày đăng, danh mục; lượt xem (`views`) tăng thêm 1 | |
| 2 | Truy cập bài viết chưa xuất bản qua URL trực tiếp | Điều hướng đến slug của bài viết có `published_at` trong tương lai | Hệ thống trả về 404 hoặc từ chối truy cập | |
| 3 | Truy cập bài viết không tồn tại | URL slug sai hoặc bài viết đã bị xóa | Hệ thống trả về trang 404 | |

---

## Nhóm F — Admin: Đăng nhập & Phân quyền

### F1 — Đăng nhập Admin

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Đăng nhập admin thành công | Email và mật khẩu đúng của tài khoản có quyền admin | Chuyển hướng vào trang Dashboard admin; hiển thị đúng tên tài khoản và vai trò | |
| 2 | Đăng nhập với mật khẩu sai | Email đúng, mật khẩu sai | Hiển thị thông báo lỗi xác thực; không cho phép đăng nhập | |
| 3 | Đăng nhập bằng tài khoản không có quyền admin | Tài khoản user thông thường thử truy cập `/admin/login` | Hệ thống từ chối; hiển thị thông báo "Bạn không có quyền truy cập" hoặc chuyển hướng về trang không có quyền | |
| 4 | Truy cập trang admin khi chưa đăng nhập | Điều hướng trực tiếp đến `/admin` | Hệ thống chuyển hướng về `/admin/login` | |
| 5 | Đăng xuất khỏi admin | Nhấn nút Đăng xuất trong Admin Panel | Phiên admin bị hủy; chuyển hướng về `/admin/login`; không thể truy cập lại trang admin khi chưa đăng nhập mới | |

---

### F2 — Phân quyền vai trò (Roles & Permissions)

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tài khoản có đủ quyền truy cập Resource | Đăng nhập bằng tài khoản có permission tương ứng, truy cập Resource | Hiển thị đầy đủ danh sách, các nút thêm/sửa/xóa hoạt động bình thường | |
| 2 | Tài khoản không có quyền truy cập Resource | Đăng nhập bằng tài khoản thiếu permission, truy cập Resource bị hạn chế | Hệ thống ẩn menu hoặc trả về lỗi 403; không hiển thị dữ liệu nhạy cảm | |
| 3 | Tạo vai trò mới và gán quyền | Admin tạo role `Warehouse Staff`, gán các permission liên quan đến kho | Role được tạo; tài khoản được gán role đó chỉ thấy và thao tác được các Resource được phép | |
| 4 | Gỡ quyền khỏi vai trò | Admin xóa permission `view_orders` khỏi một role | Tài khoản thuộc role đó lập tức mất quyền xem đơn hàng sau khi làm mới phiên | |

---

### F3 — Quản lý tài khoản người dùng

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách người dùng | Admin truy cập trang quản lý Users | Hiển thị danh sách tài khoản với các thông tin: tên, email, vai trò, trạng thái, ngày tạo | |
| 2 | Tìm kiếm và lọc người dùng | Nhập tên hoặc email vào ô tìm kiếm | Danh sách lọc đúng theo từ khóa; kết quả phản ánh chính xác | |
| 3 | Gán vai trò cho người dùng | Admin chọn một tài khoản, gán role `Warehouse Staff` | Role được lưu; tài khoản đó nhận ngay quyền tương ứng khi đăng nhập lại | |
| 4 | Vô hiệu hóa tài khoản người dùng | Admin đặt trạng thái tài khoản thành không hoạt động | Tài khoản không thể đăng nhập; hiển thị thông báo phù hợp khi cố đăng nhập | |

---

## Nhóm G — Admin: Danh mục & Sản phẩm

### G1 — CRUD Danh mục

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo danh mục mới hợp lệ | Tên danh mục, slug, không có danh mục cha | Danh mục được lưu; hiển thị trong danh sách; slug tự động hoặc theo nhập | |
| 2 | Tạo danh mục con (nested) | Tên danh mục, chọn danh mục cha có sẵn | Danh mục con được lưu và liên kết đúng với danh mục cha; hiển thị đúng cấu trúc cây | |
| 3 | Tạo danh mục với tên trùng | Nhập tên đã tồn tại trong hệ thống | Hệ thống cảnh báo hoặc từ chối; không tạo bản ghi trùng | |
| 4 | Tạo danh mục với trường bắt buộc để trống | Để trống trường Tên, nhấn Lưu | Hiển thị lỗi xác thực; không lưu danh mục | |
| 5 | Chỉnh sửa tên danh mục | Thay đổi tên danh mục đã có, nhấn Lưu | Tên mới được cập nhật; phản ánh ngay trên frontend và admin | |
| 6 | Xóa danh mục không có sản phẩm | Xóa danh mục rỗng | Danh mục bị xóa khỏi hệ thống; không còn hiển thị trong danh sách | |
| 7 | Xóa danh mục đang có sản phẩm | Xóa danh mục còn chứa sản phẩm | Hệ thống cảnh báo hoặc từ chối; không cho phép xóa để tránh mất liên kết dữ liệu | |

---

### G2 — CRUD Thương hiệu

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo thương hiệu mới hợp lệ | Tên thương hiệu, logo (tuỳ chọn) | Thương hiệu được lưu; hiển thị trong danh sách quản lý | |
| 2 | Tạo thương hiệu với tên trùng | Nhập tên thương hiệu đã tồn tại | Hệ thống cảnh báo hoặc từ chối; không tạo bản ghi trùng | |
| 3 | Chỉnh sửa thông tin thương hiệu | Thay đổi tên hoặc logo của thương hiệu đã có | Thông tin được cập nhật; sản phẩm thuộc thương hiệu này phản ánh tên mới | |
| 4 | Xóa thương hiệu không có sản phẩm | Xóa thương hiệu chưa có sản phẩm nào liên kết | Thương hiệu bị xóa thành công | |
| 5 | Xóa thương hiệu đang có sản phẩm | Xóa thương hiệu còn chứa sản phẩm | Hệ thống cảnh báo hoặc từ chối; không cho phép xóa | |

---

### G3 — CRUD Bộ sưu tập

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo bộ sưu tập mới hợp lệ | Tên, slug, bật `show_on_home` | Bộ sưu tập được lưu; hiển thị trên trang chủ nếu `show_on_home = true` | |
| 2 | Tạo bộ sưu tập con (nested) | Tên, chọn bộ sưu tập cha có sẵn | Bộ sưu tập con được lưu và liên kết đúng với bộ sưu tập cha | |
| 3 | Gán sản phẩm vào bộ sưu tập | Chọn nhiều sản phẩm, gán vào bộ sưu tập | Quan hệ N:M được lưu đúng; sản phẩm hiển thị trong trang bộ sưu tập tương ứng | |
| 4 | Gỡ sản phẩm khỏi bộ sưu tập | Bỏ chọn sản phẩm khỏi bộ sưu tập, nhấn Lưu | Sản phẩm không còn xuất hiện trong bộ sưu tập; các bảng khác không bị ảnh hưởng | |
| 5 | Xóa bộ sưu tập | Xóa bộ sưu tập bất kỳ | Bộ sưu tập bị xóa; các sản phẩm thuộc bộ sưu tập đó không bị xóa theo | |

---

### G4 — CRUD Sản phẩm

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo sản phẩm mới hợp lệ | Tên, giá, danh mục, thương hiệu, ảnh đại diện | Sản phẩm được lưu; hiển thị trên frontend theo URL `/{categorySlug}/{productSlug}` | |
| 2 | Tạo sản phẩm với gallery ảnh | Tải lên nhiều ảnh cho sản phẩm | Tất cả ảnh được lưu vào `product_images`; hiển thị đúng trong gallery trang chi tiết | |
| 3 | Tạo sản phẩm với thông số kỹ thuật | Nhập các trường specs (RAM, bộ nhớ, màn hình...) | Thông số được lưu; hiển thị đúng trong tab thông số trên trang chi tiết sản phẩm | |
| 4 | Tạo sản phẩm với trường bắt buộc để trống | Để trống trường Tên hoặc Giá | Hiển thị lỗi xác thực; không lưu sản phẩm | |
| 5 | Chỉnh sửa thông tin sản phẩm | Thay đổi giá bán, mô tả, ảnh đại diện | Thông tin cập nhật; phản ánh ngay trên trang chi tiết sản phẩm | |
| 6 | Soft delete sản phẩm | Admin xóa sản phẩm | Sản phẩm bị soft delete (`deleted_at` được gán); không còn hiển thị trên frontend; dữ liệu vẫn tồn tại trong database | |
| 7 | Khôi phục sản phẩm đã soft delete | Admin khôi phục sản phẩm đã xóa | `deleted_at` được đặt lại về null; sản phẩm hiển thị lại trên frontend | |

---

## Nhóm H — Admin: Quản lý Đơn hàng

### H1 — Xem & cập nhật trạng thái đơn hàng

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách đơn hàng | Admin truy cập trang quản lý Orders | Hiển thị toàn bộ đơn hàng với mã đơn, tên khách, tổng tiền, trạng thái, ngày đặt; hỗ trợ lọc và tìm kiếm | |
| 2 | Lọc đơn hàng theo trạng thái | Chọn lọc theo `pending` / `confirmed` / `shipping` / `delivered` / `cancelled` | Danh sách chỉ hiển thị đơn hàng đúng trạng thái đã chọn | |
| 3 | Xem chi tiết đơn hàng | Nhấp vào một đơn hàng | Hiển thị đầy đủ: danh sách sản phẩm, số lượng, đơn giá, địa chỉ giao hàng, phương thức thanh toán, lịch sử trạng thái | |
| 4 | Cập nhật trạng thái đơn từ `pending` sang `confirmed` | Admin chọn đơn `pending`, cập nhật sang `confirmed` | Trạng thái thay đổi; nhật ký hoạt động ghi nhận hành động; khách hàng có thể thấy trạng thái mới | |
| 5 | Cập nhật trạng thái đơn từ `confirmed` sang `shipping` | Admin cập nhật sang `shipping` | Trạng thái thay đổi; hệ thống tự động tạo phiếu xuất kho (GoodsIssue) và trừ tồn kho theo FIFO | |

---

### H2 — Vòng đời đơn hàng (pending → delivered / cancelled)

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Đi đúng vòng đời thuận: pending → confirmed → shipping → delivered | Admin cập nhật từng bước theo thứ tự | Mỗi bước chuyển trạng thái thành công; nhật ký ghi nhận đầy đủ; tồn kho bị trừ khi sang `shipping` | |
| 2 | Chuyển thẳng từ `pending` sang `shipping` (bỏ qua `confirmed`) | Admin cố cập nhật trực tiếp `pending` → `shipping` | Hệ thống từ chối hoặc cho phép tùy theo cấu hình nghiệp vụ; kết quả nhất quán và có thông báo rõ ràng | |
| 3 | Cố chuyển ngược trạng thái (`delivered` → `pending`) | Admin cố đặt lại trạng thái về bước trước | Hệ thống từ chối; trạng thái không thay đổi; hiển thị thông báo lỗi | |
| 4 | Đơn hàng đạt trạng thái `delivered` | Admin cập nhật sang `delivered` | Trạng thái cuối được xác nhận; không thể hủy thêm; nhật ký hoàn chỉnh | |

---

### H3 — Hủy đơn → hoàn kho + hoàn voucher

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Admin hủy đơn ở trạng thái `pending` | Chọn đơn `pending`, cập nhật sang `cancelled` | Đơn chuyển sang `cancelled`; tồn kho (`product.stock`) được hoàn trả; voucher (nếu có) được hoàn về trạng thái chưa dùng | |
| 2 | Admin hủy đơn ở trạng thái `confirmed` | Chọn đơn `confirmed`, cập nhật sang `cancelled` | Tương tự case 1; các side effect hoàn kho và hoàn voucher được thực thi đầy đủ | |
| 3 | Admin hủy đơn đã ở trạng thái `shipping` (đã xuất kho) | Chọn đơn `shipping`, cập nhật sang `cancelled` | Đơn hủy; hệ thống hoàn lại `remaining_quantity` cho đúng các lô hàng FIFO đã xuất; voucher được hoàn nếu có | |
| 4 | Kiểm tra tồn kho sau khi hủy | Sau khi hủy đơn có 2 sản phẩm A (qty: 3) và B (qty: 1) | Tồn kho sản phẩm A tăng thêm 3, sản phẩm B tăng thêm 1; số liệu chính xác tuyệt đối | |
| 5 | Hủy đơn không dùng voucher | Hủy đơn hàng đặt theo phương thức COD, không áp dụng mã giảm giá | Đơn hủy thành công; không có thao tác hoàn voucher; tồn kho hoàn bình thường | |

---

### H4 — Tải hóa đơn PDF

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tải hóa đơn PDF đơn hàng hợp lệ | Admin nhấn nút xuất PDF trên đơn hàng bất kỳ | File PDF được tạo và tải xuống; nội dung bao gồm mã đơn, thông tin khách hàng, danh sách sản phẩm, tổng tiền | |
| 2 | Kiểm tra nội dung hóa đơn PDF | Mở file PDF vừa tải | Thông tin khớp chính xác với chi tiết đơn hàng trên hệ thống; định dạng rõ ràng, đọc được | |
| 3 | Tải PDF đơn hàng đã hủy | Admin xuất PDF trên đơn `cancelled` | PDF được tạo thành công; trạng thái "Đã hủy" hiển thị rõ ràng trong hóa đơn | |
| 4 | Truy cập route xuất PDF khi không có quyền | Tài khoản không có quyền admin cố truy cập route `/admin/orders/{id}/invoice` | Hệ thống từ chối; trả về lỗi 403 hoặc chuyển hướng về trang đăng nhập admin | |

---

## Nhóm I — Admin: Kho hàng

### I1 — Tạo phiếu nhập kho

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo phiếu nhập kho hợp lệ | Chọn đối tác nhà cung cấp, thêm sản phẩm, số lượng, giá nhập | Phiếu nhập kho được lưu; bản ghi `goods_receipt_details` tạo đúng với `remaining_quantity` bằng số lượng nhập | |
| 2 | Tạo phiếu nhập với nhiều sản phẩm | Thêm nhiều dòng sản phẩm khác nhau trong cùng một phiếu | Tất cả dòng sản phẩm được lưu đúng; mỗi dòng tạo một batch riêng biệt | |
| 3 | Tạo phiếu nhập với trường bắt buộc để trống | Không chọn nhà cung cấp hoặc không nhập số lượng | Hiển thị lỗi xác thực; không lưu phiếu | |
| 4 | Tạo phiếu nhập với số lượng bằng 0 hoặc âm | Nhập `quantity = 0` hoặc `-5` | Hệ thống từ chối; hiển thị lỗi xác thực số lượng phải lớn hơn 0 | |
| 5 | Xem chi tiết phiếu nhập kho đã tạo | Nhấp vào phiếu nhập trong danh sách | Hiển thị đầy đủ thông tin: nhà cung cấp, ngày nhập, danh sách sản phẩm, số lượng, giá nhập từng dòng | |

---

### I2 — Tự động cập nhật tồn kho khi nhập

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tồn kho tăng sau khi tạo phiếu nhập | Tạo phiếu nhập sản phẩm A với số lượng 50 | `product.stock` của sản phẩm A tăng thêm 50; `goods_receipt_details.remaining_quantity` = 50 | |
| 2 | Nhập nhiều lô khác nhau cho cùng sản phẩm | Tạo hai phiếu nhập cho sản phẩm A: lần 1 nhập 30, lần 2 nhập 20 | Tổng `product.stock` = 50; hệ thống tạo 2 batch riêng biệt với `created_at` khác nhau để phục vụ FIFO | |
| 3 | Kiểm tra tồn kho trên trang Inventory | Sau khi nhập kho, vào trang tổng quan tồn kho | Số liệu tồn kho phản ánh đúng tổng `remaining_quantity` của tất cả batch chưa hết | |

---

### I3 — Xuất kho FIFO khi đơn sang "shipping"

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xuất kho tự động khi đơn chuyển sang `shipping` | Admin cập nhật trạng thái đơn hàng sang `shipping` | Hệ thống tự động tạo phiếu xuất kho (`GoodsIssue`); `InventoryService::reduceStock()` được gọi; tồn kho giảm đúng số lượng | |
| 2 | Kiểm tra nguyên tắc FIFO | Sản phẩm A có 2 batch: batch cũ (còn 5), batch mới (còn 10); đơn hàng đặt 7 | Batch cũ bị xuất trước: `remaining_quantity` giảm từ 5 xuống 0; batch mới giảm từ 10 xuống 8; đúng thứ tự FIFO | |
| 3 | Xuất kho khi tồn kho vừa đủ | Đặt hàng số lượng bằng đúng tổng tồn kho hiện có | Xuất kho thành công; toàn bộ batch về `remaining_quantity = 0`; `product.stock = 0` | |
| 4 | Phiếu xuất kho ghi đúng COGS | Kiểm tra bản ghi `goods_issue_details` sau khi xuất | Mỗi dòng ghi đúng `quantity`, `import_price` (giá vốn từ batch tương ứng) phục vụ tính COGS | |

---

### I4 — Hoàn kho khi hủy đơn

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Hoàn kho khi hủy đơn chưa xuất kho (`pending` / `confirmed`) | Admin hủy đơn ở trạng thái `pending` hoặc `confirmed` | `product.stock` tăng lại đúng số lượng; không có thao tác FIFO vì chưa xuất batch | |
| 2 | Hoàn kho FIFO khi hủy đơn đã xuất (`shipping`) | Admin hủy đơn đang ở trạng thái `shipping` | `remaining_quantity` của các batch đã xuất được cộng lại đúng thứ tự ngược; `product.stock` khớp với tổng `remaining_quantity` | |
| 3 | Kiểm tra tồn kho sau hoàn kho | So sánh số liệu tồn kho trước và sau khi hủy đơn | Tồn kho sau hoàn kho bằng đúng tồn kho trước khi đơn được tạo; không có sai lệch | |
| 4 | Hủy nhiều đơn hàng liên tiếp cho cùng sản phẩm | Hủy lần lượt 3 đơn hàng đều có sản phẩm A | Mỗi lần hủy tồn kho tăng đúng số lượng tương ứng; tổng cuối cùng chính xác | |

---

### I5 — Xem tổng quan tồn kho

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem trang tổng quan tồn kho | Admin truy cập trang Inventory | Hiển thị danh sách sản phẩm với số lượng tồn hiện tại; dữ liệu chính xác, đồng bộ với các batch | |
| 2 | Widget hàng tồn kho lâu (Dead Stock) | Truy cập Dashboard admin | Widget `DeadStockWidget` hiển thị đúng các sản phẩm có batch nhập lâu ngày mà `remaining_quantity > 0` | |
| 3 | Biểu đồ biến động nhập/xuất kho | Xem widget `StockMovementChart` trên Dashboard | Biểu đồ phản ánh đúng lịch sử nhập và xuất kho theo thời gian; không hiển thị lỗi khi chưa có dữ liệu | |
| 4 | Trang Inventory chỉ cho phép xem, không sửa | Admin thử thao tác chỉnh sửa trực tiếp trên trang Inventory | Không có nút thêm/sửa/xóa; trang hoạt động ở chế độ view-only theo đúng thiết kế `InventoryResource` | |

---

## Nhóm J — Admin: Nội dung & Tiện ích

### J1 — CRUD Voucher

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo voucher giảm theo phần trăm | Loại `percent`, giá trị 15%, `max_discount` 100.000đ, điều kiện đơn tối thiểu 300.000đ | Voucher được lưu; áp dụng đúng logic khi thanh toán (giảm tối đa 100.000đ dù 15% vượt mức) | |
| 2 | Tạo voucher giảm số tiền cố định | Loại `fixed`, giá trị 50.000đ | Voucher được lưu; khi áp dụng tổng đơn giảm đúng 50.000đ | |
| 3 | Tạo voucher với thời hạn sử dụng | Nhập `start_date` và `expired_at` hợp lệ | Voucher chỉ hoạt động trong khoảng thời gian được thiết lập | |
| 4 | Tạo voucher với số lượt dùng tối đa | Nhập `max_uses = 100` | Sau khi đủ 100 lượt sử dụng, voucher tự động từ chối áp dụng thêm | |
| 5 | Tạo voucher với trường bắt buộc để trống | Để trống mã voucher hoặc giá trị giảm | Hiển thị lỗi xác thực; không lưu voucher | |
| 6 | Chỉnh sửa voucher đã có | Thay đổi giá trị giảm hoặc hạn sử dụng | Thông tin cập nhật; áp dụng ngay cho các lần dùng tiếp theo | |
| 7 | Xóa voucher | Admin xóa một voucher | Voucher bị xóa; người dùng đã lưu voucher đó không thể áp dụng thêm | |

---

### J2 — Quản lý đánh giá (ẩn/hiện, phản hồi)

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem danh sách đánh giá | Admin truy cập trang quản lý Reviews | Hiển thị tất cả đánh giá với tên người dùng, sản phẩm, rating, nội dung, trạng thái ẩn/hiện | |
| 2 | Ẩn đánh giá vi phạm | Admin bật `is_hidden = true` cho một đánh giá | Đánh giá không còn hiển thị trên trang chi tiết sản phẩm phía frontend; vẫn tồn tại trong admin | |
| 3 | Hiện lại đánh giá đã ẩn | Admin tắt `is_hidden = false` | Đánh giá hiển thị lại trên frontend | |
| 4 | Phản hồi đánh giá của khách hàng | Admin nhập nội dung vào trường `admin_reply`, lưu | Phản hồi được lưu; hiển thị đúng bên dưới đánh giá của khách trên trang chi tiết sản phẩm | |
| 5 | Lọc đánh giá theo sản phẩm hoặc rating | Chọn lọc theo sản phẩm cụ thể hoặc rating 1 sao | Danh sách hiển thị đúng các đánh giá thỏa điều kiện lọc | |

---

### J3 — CRUD Blog & Banner

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Tạo bài viết mới và lên lịch đăng | Tiêu đề, nội dung, `published_at` trong tương lai | Bài viết được lưu; chưa hiển thị trên frontend cho đến khi đến giờ đã lên lịch | |
| 2 | Tạo bài viết và đăng ngay | `published_at` là thời điểm hiện tại hoặc quá khứ | Bài viết hiển thị ngay trên trang blog frontend | |
| 3 | Chỉnh sửa bài viết đã đăng | Thay đổi tiêu đề hoặc nội dung bài viết | Nội dung cập nhật; phản ánh ngay trên trang chi tiết bài viết | |
| 4 | Xóa bài viết | Admin xóa một bài viết | Bài viết không còn hiển thị trên frontend; xóa khỏi danh sách quản lý | |
| 5 | Tạo banner mới | Nhập tiêu đề, tải ảnh, đặt thứ tự hiển thị | Banner được lưu; hiển thị đúng vị trí trên trang chủ theo thứ tự đã thiết lập | |
| 6 | Chỉnh sửa thứ tự banner | Thay đổi trường `order` của hai banner | Thứ tự hiển thị trên trang chủ thay đổi theo đúng giá trị mới | |
| 7 | Xóa banner | Admin xóa một banner | Banner không còn hiển thị trên trang chủ | |

---

### J4 — Xem nhật ký hoạt động

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Xem nhật ký đơn hàng | Admin truy cập trang `OrderActivityResource` | Hiển thị danh sách log liên quan đến đơn hàng: thay đổi trạng thái, hủy đơn, xuất kho; mỗi bản ghi có thời gian, người thực hiện, mô tả hành động | |
| 2 | Xem nhật ký hệ thống | Admin truy cập trang `SystemActivityResource` | Hiển thị các log hệ thống không liên quan đến đơn hàng; phân loại đúng với nhật ký đơn hàng | |
| 3 | Lọc nhật ký theo khoảng thời gian | Chọn từ ngày đến ngày trong bộ lọc | Chỉ hiển thị bản ghi log trong khoảng thời gian đã chọn | |
| 4 | Nhật ký ghi nhận sau hành động thay đổi trạng thái đơn | Admin cập nhật trạng thái đơn hàng | Bản ghi log mới được tạo ngay; ghi đúng trạng thái cũ, trạng thái mới, người thực hiện và thời điểm | |

---

### J5 — Tự động xóa log > 90 ngày

| Case | Mô tả | Đầu vào | Kết quả mong đợi | Pass/Fail |
|------|-------|---------|-----------------|-----------|
| 1 | Log cũ hơn 90 ngày bị xóa tự động | Hệ thống chạy lệnh `model:prune` (Prunable) | Toàn bộ bản ghi `activity_logs` có `created_at` cách hiện tại hơn 90 ngày bị xóa khỏi database | |
| 2 | Log trong vòng 90 ngày không bị xóa | Kiểm tra các bản ghi log có `created_at` trong 90 ngày gần nhất | Các bản ghi này không bị ảnh hưởng; dữ liệu toàn vẹn | |
| 3 | Kiểm tra sau khi prune | Truy vấn bảng `activity_logs` sau khi chạy prune | Không còn bản ghi nào có `created_at` vượt ngưỡng 90 ngày; tổng số bản ghi giảm đúng | |
