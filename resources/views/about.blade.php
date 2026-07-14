@extends('layouts.app')

@section('content')
<div class="bg-gray-50">

    {{-- ====================== HERO ====================== --}}
    <section class="relative overflow-hidden" style="background: linear-gradient(135deg, #fed700 0%, #ffd200 60%, #ffb800 100%);">
        <div class="container mx-auto px-4 py-16 md:py-24 relative z-10">
            <div class="max-w-3xl">
                <p class="uppercase tracking-widest text-brand-dark/70 text-sm font-semibold mb-3">
                    Giới thiệu về chúng tôi
                </p>
                <h1 class="text-4xl md:text-5xl font-extrabold text-brand-dark leading-tight mb-5">
                    Thế Giới Di Động — <br class="hidden md:block">
                    Nơi bắt đầu hành trình công nghệ của bạn
                </h1>
                <p class="text-brand-dark/80 text-lg leading-relaxed max-w-2xl">
                    Chúng tôi mang đến những sản phẩm điện thoại, laptop, máy tính bảng và phụ kiện
                    chính hãng với mức giá tốt nhất, cùng dịch vụ hậu mãi tận tâm — vì sự hài lòng
                    của mỗi khách hàng là thước đo thành công của Thế Giới Di Động.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 bg-brand-dark text-white px-6 py-3 rounded-full font-semibold hover:bg-black transition">
                        <i class="fa-solid fa-cart-shopping"></i> Mua sắm ngay
                    </a>
                    <a href="#lien-he"
                       class="inline-flex items-center gap-2 bg-white/90 text-brand-dark px-6 py-3 rounded-full font-semibold hover:bg-white transition">
                        <i class="fa-solid fa-phone"></i> Liên hệ tư vấn
                    </a>
                </div>
            </div>
        </div>
        {{-- decorative circles --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-16 w-80 h-80 rounded-full bg-orange-300/30 blur-3xl"></div>
    </section>

    {{-- ====================== STATS ====================== --}}
    <section class="container mx-auto px-4 -mt-10 relative z-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-extrabold text-brand-blue">3.000+</div>
                <div class="text-sm text-gray-600 mt-1">Cửa hàng toàn quốc</div>
            </div>
            <div class="text-center border-l border-gray-100">
                <div class="text-3xl md:text-4xl font-extrabold text-brand-blue">10 triệu+</div>
                <div class="text-sm text-gray-600 mt-1">Khách hàng tin dùng</div>
            </div>
            <div class="text-center md:border-l border-gray-100">
                <div class="text-3xl md:text-4xl font-extrabold text-brand-blue">30.000+</div>
                <div class="text-sm text-gray-600 mt-1">Nhân viên tận tâm</div>
            </div>
            <div class="text-center border-l border-gray-100">
                <div class="text-3xl md:text-4xl font-extrabold text-brand-blue">20+ năm</div>
                <div class="text-sm text-gray-600 mt-1">Kinh nghiệm phục vụ</div>
            </div>
        </div>
    </section>

    {{-- ====================== VỀ CHÚNG TÔI ====================== --}}
    <section class="container mx-auto px-4 py-16">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-brand-blue font-semibold uppercase text-sm mb-2">Về chúng tôi</p>
                <h2 class="text-3xl md:text-4xl font-bold text-brand-dark mb-6">
                    Đồng hành cùng khách hàng trên hành trình công nghệ
                </h2>
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        <strong>Thế Giới Di Động</strong> là hệ thống bán lẻ thiết bị công nghệ hàng đầu tại Việt Nam,
                        chuyên phân phối điện thoại di động, laptop, máy tính bảng, smartwatch và phụ kiện chính hãng.
                    </p>
                    <p>
                        Từ một cửa hàng nhỏ, đến nay chúng tôi đã phát triển thành hệ thống trải rộng khắp
                        64 tỉnh thành, phục vụ hàng triệu lượt khách mỗi năm bằng đội ngũ nhân viên chuyên nghiệp
                        và tận tâm.
                    </p>
                    <p>
                        Chúng tôi cam kết mang đến trải nghiệm mua sắm thuận tiện, minh bạch về giá, cùng chính
                        sách bảo hành – đổi trả rõ ràng, giúp khách hàng an tâm lựa chọn sản phẩm phù hợp nhất.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-video rounded-2xl overflow-hidden shadow-xl bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="w-24 h-24 mx-auto rounded-full bg-brand-yellow flex items-center justify-center mb-4 shadow-md">
                            <i class="fa-solid fa-mobile-screen-button text-4xl text-brand-dark"></i>
                        </div>
                        <p class="text-brand-dark font-bold text-xl">thegioididong.com</p>
                        <p class="text-gray-600 text-sm mt-1">Chuỗi bán lẻ công nghệ #1 Việt Nam</p>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 bg-brand-blue text-white rounded-xl p-4 shadow-lg hidden md:block">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-award text-3xl"></i>
                        <div>
                            <div class="font-bold">Top 50</div>
                            <div class="text-xs opacity-90">Doanh nghiệp niêm yết tốt nhất</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== TẦM NHÌN - SỨ MỆNH - GIÁ TRỊ ====================== --}}
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <p class="text-brand-blue font-semibold uppercase text-sm mb-2">Định hướng của chúng tôi</p>
                <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">
                    Tầm nhìn – Sứ mệnh – Giá trị
                </h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Tầm nhìn --}}
                <div class="p-8 rounded-2xl border border-gray-100 hover:border-brand-yellow hover:shadow-lg transition group">
                    <div class="w-14 h-14 rounded-xl bg-yellow-100 group-hover:bg-brand-yellow flex items-center justify-center mb-5 transition">
                        <i class="fa-solid fa-eye text-2xl text-brand-dark"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Tầm nhìn</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Trở thành chuỗi bán lẻ thiết bị công nghệ và điện tử tiêu dùng lớn nhất Đông Nam Á,
                        được khách hàng yêu quý và tin tưởng.
                    </p>
                </div>
                {{-- Sứ mệnh --}}
                <div class="p-8 rounded-2xl border border-gray-100 hover:border-brand-blue hover:shadow-lg transition group">
                    <div class="w-14 h-14 rounded-xl bg-blue-100 group-hover:bg-brand-blue flex items-center justify-center mb-5 transition">
                        <i class="fa-solid fa-bullseye text-2xl text-brand-blue group-hover:text-white transition"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Sứ mệnh</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Mang đến sản phẩm công nghệ chính hãng, chất lượng cao với giá tốt nhất, cùng dịch vụ
                        chăm sóc khách hàng vượt trội — góp phần nâng cao chất lượng cuộc sống người Việt.
                    </p>
                </div>
                {{-- Giá trị --}}
                <div class="p-8 rounded-2xl border border-gray-100 hover:border-red-400 hover:shadow-lg transition group">
                    <div class="w-14 h-14 rounded-xl bg-red-100 group-hover:bg-red-400 flex items-center justify-center mb-5 transition">
                        <i class="fa-solid fa-heart text-2xl text-red-500 group-hover:text-white transition"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Giá trị cốt lõi</h3>
                    <ul class="text-gray-600 leading-relaxed space-y-1">
                        <li>• <strong>Tận tâm</strong> với khách hàng</li>
                        <li>• <strong>Trung thực</strong> trong giao dịch</li>
                        <li>• <strong>Chính trực</strong> trong quản lý</li>
                        <li>• <strong>Yêu thương & hỗ trợ</strong> đồng đội</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== LỊCH SỬ ====================== --}}
    <section class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <p class="text-brand-blue font-semibold uppercase text-sm mb-2">Hành trình phát triển</p>
            <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">Dấu ấn qua các cột mốc</h2>
        </div>
        <div class="relative max-w-4xl mx-auto">
            <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-brand-yellow via-brand-blue to-transparent md:-ml-px"></div>
            @php
                $milestones = [
                    ['year' => '2004', 'title' => 'Khởi nghiệp', 'text' => 'Cửa hàng đầu tiên tại TP.HCM chuyên kinh doanh điện thoại di động.'],
                    ['year' => '2007', 'title' => 'Chuỗi bán lẻ', 'text' => 'Mở rộng thành chuỗi 40+ cửa hàng, ứng dụng chuẩn ISO trong quản lý.'],
                    ['year' => '2014', 'title' => 'Niêm yết HOSE', 'text' => 'Chính thức niêm yết trên sàn chứng khoán TP.HCM (mã MWG).'],
                    ['year' => '2018', 'title' => '1.000+ cửa hàng', 'text' => 'Chạm mốc 1.000 siêu thị Thế Giới Di Động và Điện Máy Xanh.'],
                    ['year' => '2023', 'title' => 'Dẫn đầu thị trường', 'text' => 'Doanh thu hơn 100.000 tỷ đồng, khẳng định vị thế số 1 ngành bán lẻ công nghệ.'],
                ];
            @endphp
            @foreach($milestones as $i => $m)
                <div class="relative mb-10 flex flex-col md:flex-row md:items-center {{ $i % 2 === 1 ? 'md:flex-row-reverse' : '' }}">
                    <div class="md:w-1/2 md:px-8 pl-12 md:pl-0">
                        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition">
                            <div class="text-brand-blue font-extrabold text-2xl mb-1">{{ $m['year'] }}</div>
                            <h4 class="font-bold text-brand-dark mb-2">{{ $m['title'] }}</h4>
                            <p class="text-gray-600 text-sm">{{ $m['text'] }}</p>
                        </div>
                    </div>
                    {{-- Dot on timeline --}}
                    <div class="absolute left-4 md:left-1/2 top-6 md:top-1/2 -ml-1.5 md:-ml-2 w-3 h-3 md:w-4 md:h-4 rounded-full bg-brand-yellow border-4 border-white shadow"></div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ====================== CAM KẾT ====================== --}}
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <p class="text-brand-blue font-semibold uppercase text-sm mb-2">Vì khách hàng</p>
                <h2 class="text-3xl md:text-4xl font-bold text-brand-dark">Cam kết của chúng tôi</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $commits = [
                        ['icon' => 'fa-shield-halved', 'title' => 'Hàng chính hãng', 'text' => '100% sản phẩm nhập khẩu chính hãng, đầy đủ tem, hoá đơn VAT.'],
                        ['icon' => 'fa-truck-fast', 'title' => 'Giao hàng nhanh', 'text' => 'Giao trong 1–3 ngày nội thành, miễn phí cho đơn từ 500.000đ.'],
                        ['icon' => 'fa-rotate-left', 'title' => 'Đổi trả 7 ngày', 'text' => 'Đổi trả miễn phí trong 7 ngày nếu sản phẩm còn nguyên vẹn.'],
                        ['icon' => 'fa-headset', 'title' => 'Hỗ trợ 24/7', 'text' => 'Tổng đài 1800.1060 hoạt động từ 7:30 đến 22:00 hàng ngày.'],
                    ];
                @endphp
                @foreach($commits as $c)
                    <div class="text-center p-5">
                        <div class="w-16 h-16 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-4">
                            <i class="fa-solid {{ $c['icon'] }} text-2xl text-brand-dark"></i>
                        </div>
                        <h4 class="font-bold text-brand-dark mb-2">{{ $c['title'] }}</h4>
                        <p class="text-gray-600 text-sm">{{ $c['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ====================== LIÊN HỆ ====================== --}}
    <section id="lien-he" class="container mx-auto px-4 py-16">
        <div class="bg-gradient-to-r from-brand-dark to-gray-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="grid md:grid-cols-2 gap-8 p-8 md:p-12 text-white">
                <div>
                    <p class="text-brand-yellow font-semibold uppercase text-sm mb-2">Liên hệ với chúng tôi</p>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Cần tư vấn?<br>Shop luôn sẵn sàng.</h2>
                    <p class="text-gray-300 leading-relaxed mb-6">
                        Đội ngũ tư vấn viên chuyên nghiệp sẽ hỗ trợ bạn 24/7 qua nhiều kênh khác nhau.
                        Bạn cũng có thể trò chuyện ngay với trợ lý AI ở góc phải màn hình.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-yellow/20 flex items-center justify-center">
                                <i class="fa-solid fa-phone text-brand-yellow"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Hotline miễn phí</div>
                                <div class="font-bold text-lg">1800.1060</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-yellow/20 flex items-center justify-center">
                                <i class="fa-solid fa-envelope text-brand-yellow"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Email hỗ trợ</div>
                                <div class="font-bold">support@thegioididong.com</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-yellow/20 flex items-center justify-center">
                                <i class="fa-solid fa-location-dot text-brand-yellow"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Trụ sở chính</div>
                                <div class="font-bold">128 Trần Quang Khải, Q.1, TP.HCM</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/5 rounded-xl p-6 backdrop-blur">
                    <h3 class="font-bold text-xl mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-brand-yellow"></i> Giờ làm việc
                    </h3>
                    <div class="space-y-2 text-gray-200">
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span>Thứ 2 – Thứ 6</span>
                            <span class="font-semibold">7:30 – 22:00</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span>Thứ 7 – Chủ nhật</span>
                            <span class="font-semibold">8:00 – 22:00</span>
                        </div>
                        <div class="flex justify-between pb-2">
                            <span>Lễ, Tết</span>
                            <span class="font-semibold text-brand-yellow">Vẫn phục vụ</span>
                        </div>
                    </div>
                    <a href="{{ route('home') }}"
                       class="mt-6 inline-flex items-center gap-2 bg-brand-yellow text-brand-dark px-5 py-2.5 rounded-full font-semibold hover:bg-yellow-400 transition w-full justify-center">
                        <i class="fa-solid fa-arrow-right"></i> Bắt đầu mua sắm
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
