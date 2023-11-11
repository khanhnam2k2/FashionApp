<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7">
                    <div class="header__top__left">
                        <p>Miễn phí vận chuyển, đảm bảo hoàn trả hoặc hoàn tiền trong 30 ngày.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5">
                    <div class="header__top__right">
                        @guest
                            <div class="header__top__links">
                                <a href="{{ route('login') }}">Đăng nhập</a>
                                <a href="{{ route('register') }}">Đăng ký</a>
                            </div>
                        @else
                            <div class="header__top__hover">
                                <span>{{ Auth::user()->name }} <i class="arrow_carrot-down"></i></span>
                                <ul style="text-align: start">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.index') }}"><i
                                                class="fa-regular fa-user mr-2"></i>Hồ sơ</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('order.index') }}"><i
                                                class="fa-solid fa-receipt mr-2"></i>Đơn mua</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Đăng xuất
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ route('home') }}">Trang
                                chủ</a>
                        </li>
                        <li class="{{ request()->is('about') ? 'active' : '' }}"><a href="{{ route('about') }}">Về
                                chúng tôi</a></li>
                        <li class="{{ request()->is('shop') ? 'active' : '' }}"><a
                                href="{{ route('shop.index') }}">Shop</a></li>
                        <li class="{{ request()->is('post') ? 'active' : '' }}"><a
                                href="{{ route('post.index') }}">Bài viết</a></li>
                        <li class="{{ request()->is('contacts') ? 'active' : '' }}"><a
                                href="{{ route('contact.show') }}">Liên hệ</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a class="position-relative" id="cartIcon" href="{{ route('cart.index') }}"><img
                            src="{{ asset('img/icon/cart.png') }}" alt="cart">
                    </a>
                    <div id="cart_list" class="position-absolute">
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas__open"><i class="fa fa-bars"></i></div>
    </div>
</header>

<script>
    /*
     * Load cart list
     */
    function searchCartList() {
        $.ajax({
            url: "{{ route('cart.searchLimit') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function(data) {
            $('#cart_list').html(data);
        }).fail(function(xhr) {
            if (xhr.status === 401) {
                $('#cart_list').html(
                    '<div class="text-center p-5"><h5><i class="fa-regular fa-face-sad-tear mr-2"></i>Chưa có sản phẩm</h5></div>'
                );
            } else {
                notiError();
            }
        });
    }

    $(document).ready(function() {

        // hover to show cart list
        $('#cartIcon,#cart_list').mouseenter(function() {
            $('#cart_list').show();
            searchCartList();
        })

        // move leave to hide cart list
        $("#cartIcon, #cart_list").mouseleave(function() {
            $('#cart_list').hide();
        });
    })
</script>
