@extends('layouts.app')
@section('title', 'Male Fashion - Your Wardrobe, Your Way')
@section('content')
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="{{ asset('img/hero/hero-1.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Bộ sưu tập mùa hè</h6>
                                <h2>Bộ sưu tập Thu – Đông 2030</h2>
                                <p>Một nhãn hiệu chuyên tạo ra những sản phẩm thiết yếu sang trọng. Được tạo ra một cách có
                                    đạo đức với một thái độ kiên định
                                    cam kết chất lượng vượt trội.</p>
                                <a href="{{ route('shop.index') }}" class="primary-btn">Mua sắm ngay bây giờ <span
                                        class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa-brands fa-facebook"></i></i></a>
                                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="{{ asset('img/hero/hero-2.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Bộ sưu tập mùa hè</h6>
                                <h2>Bộ sưu tập Thu – Đông 2030</h2>
                                <p>Một nhãn hiệu chuyên tạo ra những sản phẩm thiết yếu sang trọng. Được tạo ra một cách có
                                    đạo đức với một thái độ kiên định
                                    cam kết chất lượng vượt trội.</p>
                                <a href="{{ route('shop.index') }}" class="primary-btn">Mua sắm ngay bây giờ <span
                                        class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa-brands fa-facebook"></i></i></a>
                                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->
    <!-- Banner Section Begin -->
    <section class="banner spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 offset-lg-4">
                    <div class="banner__item">
                        <div class="banner__item__pic">
                            <img src="img/banner/banner-1.jpg" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Bộ sưu tập quần áo 2030</h2>
                            <a href="{{ route('shop.index') }}">Mua sắm ngay bây giờ</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banner__item banner__item--middle">
                        <div class="banner__item__pic">
                            <img src="img/banner/banner-2.jpg" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Phụ kiện</h2>
                            <a href="{{ route('shop.index') }}">Mua sắm ngay bây giờ</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="banner__item banner__item--last">
                        <div class="banner__item__pic">
                            <img src="img/banner/banner-3.jpg" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Giày Xuân 2030</h2>
                            <a href="{{ route('shop.index') }}">Mua sắm ngay bây giờ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->
    <!-- Product Section Begin -->
    @if (count($categories) > 0)
        <section class="product spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="filter__controls">
                            <li class="active" data-filter="*">Tất cả</li>
                            @foreach ($categories as $key => $category)
                                <li data-filter=".{{ Str::slug($category->name) }}">
                                    {{ $category->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row product__filter">
                    @foreach ($products as $key => $item)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix {{ Str::slug($item->categoryName) }}">
                            <div class="product__item">
                                @php
                                    $imagesArray = json_decode($item->images, true);
                                @endphp
                                <div class="product__item__pic set-bg" data-setbg="{{ Storage::url($imagesArray[0]) }}">
                                    {{-- <span class="label">New</span> --}}
                                    <ul class="product__hover">
                                        <li><a href="#"><img src="{{ asset('img/icon/heart.png') }}"
                                                    alt=""></a>
                                        </li>
                                        <li><a href="{{ route('shop.details', $item->id) }}"><img
                                                    src="{{ asset('img/icon/search.png') }}" alt=""></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6>{{ $item->name }}</h6>
                                    <button data-id="{{ $key }}" data-product-id="{{ $item->id }}"
                                        class="add-cart btn">+ Thêm vào giỏ hàng</button>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>{{ number_format($item->price, 0, ',', '.') }}đ</h5>
                                        @php
                                            $sizes = explode(',', $item->sizes);
                                        @endphp
                                        <div class="size-group btn-group btn-group-toggle"
                                            id="size-group-{{ $key }}" data-toggle="buttons">
                                            @foreach ($sizes as $k => $size)
                                                <label class="btn {{ $k == 0 ? 'active' : '' }} ">
                                                    <input type="radio" {{ $k == 0 ? 'checked' : '' }} name="size"
                                                        id="{{ $size }}" value="{{ $size }}">
                                                    {{ $size }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Product Section End -->
    <!-- Instagram Section Begin -->
    <section class="instagram spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="instagram__pic">
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/instagram-1.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/instagram-2.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/instagram-3.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/instagram-4.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/instagram-5.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/instagram-6.jpg"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="instagram__text">
                        <h2>Instagram</h2>
                        <p>Theo dõi chúng tôi trên Instagram để biết thêm về các sản phẩm chất lượng cao từ shop chúng tôi
                        </p>
                        <h3><a class="text-danger" href="#">#Male_Fashion</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instagram Section End -->
    <!-- Latest Blog Section Begin -->
    <section class="latest spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Bài viết mới nhất</span>
                        <h2>Thời trang Xu hướng mới</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($postLimit as $post)
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{ Storage::url($post->image) }}"></div>
                            <div class="blog__item__text">
                                <span><img src="{{ asset('img/icon/calendar.png') }}" alt=""> 16 February
                                    2020</span>
                                <h5>{{ $post->title }}</h5>
                                <a href="{{ route('post.details', $post->id) }}">Đọc thêm</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Latest Blog Section End -->
@endsection
@section('web-script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-cart', function() {
                $(this).prop('disabled', true);
                const id = $(this).data('id');
                const productId = $(this).data('product-id');
                const sizeValue = $(`#size-group-${id}`).find(".active input[type='radio']").val();
                addToCart(productId, 1, sizeValue, $(this));
            })
        })
    </script>
@endsection
