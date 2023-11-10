@extends('layouts.app')
@section('title', 'Về chúng tôi - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Về chúng tôi</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <span>Về chúng tôi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- About Section Begin -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about__pic">
                        <img src="{{ asset('img/about/about-us.jpg') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Chúng tôi là ai ?</h4>
                        <p>Chúng tôi là đội ngũ tận tâm và sáng tạo, đặt sứ mệnh là mang đến trải nghiệm mua sắm quần áo nam
                            tốt nhất cho khách hàng. Với niềm đam mê về thời trang và sự hiểu biết sâu rộng về xu hướng,
                            chúng tôi cam kết cung cấp những sản phẩm chất lượng, thể hiện phong cách và cá nhân hóa đẳng
                            cấp.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Chúng tôi làm gì?</h4>
                        <p>Chúng tôi chuyên tạo ra không gian mua sắm trực tuyến linh hoạt và tiện lợi, nơi mà quý khách có
                            thể khám phá những bộ sưu tập đa dạng từ áo polo đến quần jeans, đáp ứng mọi nhu cầu thời trang
                            của họ. Với sự tỉ mỉ trong từng chi tiết và sự linh hoạt trong phong cách, chúng tôi hỗ trợ
                            khách hàng xây dựng phong cách riêng biệt và tự tin.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Tạo sao chọn chúng tôi?</h4>
                        <p>Chọn chúng tôi không chỉ là việc chọn mua sắm, mà còn là sự chọn lựa cho sự tự tin và phong cách.
                            Chúng tôi cam kết đem đến trải nghiệm mua sắm trực tuyến an toàn, đáng tin cậy và đầy đủ thông
                            tin để khách hàng có thể đưa ra quyết định thông tin. Sự chăm sóc khách hàng nhiệt tình và đội
                            ngũ chuyên gia thời trang của chúng tôi sẽ làm bạn hài lòng với mỗi lựa chọn.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Team Section Begin -->
    <section class="team spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Đội ngũ của chúng tôi</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="{{ asset('img/about/team-1.jpg') }}" alt="">
                        <h4>John Smith</h4>
                        <span>Fashion Design</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="{{ asset('img/about/team-2.jpg') }}" alt="">
                        <h4>Christine Wise</h4>
                        <span>C.E.O</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="{{ asset('img/about/team-3.jpg') }}" alt="">
                        <h4>Sean Robbins</h4>
                        <span>Manager</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team__item">
                        <img src="{{ asset('img/about/team-4.jpg') }}" alt="">
                        <h4>Lucy Myers</h4>
                        <span>Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Team Section End -->

    <!-- Client Section Begin -->
    <section class="clients spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Đối tác</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-1.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-2.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-3.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-4.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-5.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-6.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-7.png') }}"
                            alt=""></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6">
                    <a href="#" class="client__item"><img src="{{ asset('img/clients/client-8.png') }}"
                            alt=""></a>
                </div>
            </div>
        </div>
    </section>
    <!-- Client Section End -->
@endsection
