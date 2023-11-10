@extends('layouts.app')
@section('title', 'Đặt hàng - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Thanh toán</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <a href="{{ route('shop.index') }}">Shop</a>
                            <span>Đặt hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            @if (count($cartItems) > 0)
                <div class="checkout__form">
                    <form id="form_order">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <h6 class="checkout__title">Chi tiết đặt hàng</h6>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="checkout__input">
                                            <p>Tên của bạn<span>*</span></p>
                                            <input type="text" name="full_name" value="{{ Auth::user()->name ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Số liên hệ<span>*</span></p>
                                            <input type="text" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Địa chỉ Email<span>*</span></p>
                                            <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__input mb-2">
                                    <p>Địa chỉ giao hàng<span>*</span></p>
                                    <textarea name="address" class="form-control"></textarea>
                                </div>
                                <div class="checkout__input">
                                    <p>Mô tả thêm</p>
                                    <textarea class="form-control" name="message"
                                        placeholder="Ghi chú về đơn đặt hàng của bạn, ví dụ: ghi chú đặc biệt để giao hàng."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="checkout__order">
                                    <h4 class="order__title">Đơn hàng của bạn</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Size</th>
                                                <th>Số lượng</th>
                                                <th>Giá</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cartItems as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->productName }}
                                                    </td>
                                                    <td>
                                                        {{ $item->size }}
                                                    </td>
                                                    <td>
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($item->productPrice, 0, ',', '.') }}đ
                                                    </td>
                                                    <td>
                                                        <span>{{ number_format($item->total, 0, ',', '.') }}đ</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <ul class="checkout__total__all">
                                        <li>Tổng tiền đơn hàng: <span
                                                id="total_order">{{ number_format($totalCarts, 0, ',', '.') }}<span><span>đ</span>
                                        </li>
                                    </ul>
                                    <p> <i class="fa-solid fa-truck-fast"></i> Miễn phí giao hàng - Thanh toán khi nhận hàng
                                        (COD)</p>
                                    <button type="button" id="btn-order" class="site-btn">Đặt hàng</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center">
                    <h2>Your shopping cart is empty! Please check your cart again.</h2>
                </div>
            @endif

        </div>
    </section>
    <!-- Checkout Section End -->
@endsection

@section('web-script')
    <script>
        $(document).ready(function() {
            /**
             * Create new orer
             * @praram {formData} data - data to create new order
             * @praram {Element} btn - button click create order
             */
            function createOrder(data, btn) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('checkout.placeOrder') }}",
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                }).done(function(res) {
                    const data = res.data.original;
                    if (data.success) {
                        notiSuccess(data.success, 'center', function() {
                            window.location.href = "{{ route('home') }}";
                        });
                    } else {
                        notiError(data.error);
                    }
                }).fail(function(xhr) {
                    if (xhr.status === 400 && xhr.responseJSON.errors) {
                        const errorMessages = xhr.responseJSON.errors;
                        for (let fieldName in errorMessages) {
                            notiError(errorMessages[fieldName][0]);
                        }
                    } else {
                        notiError();
                    }
                }).always(function() {
                    btn.prop('disabled', false);
                })
            }

            // click to order 
            $('#btn-order').click(function(e) {
                e.preventDefault();
                const btnOrder = $(this);
                let formData = new FormData($('form#form_order')[0]);
                const spanContent = $("#total_order").text();
                const totalOrder = parseFloat(spanContent.replace(/\./g, ''));
                formData.append('total_order', totalOrder);
                showConfirmDialog('Bạn có chắc chắn muốn đặt đơn hàng này không?', function() {
                    btnOrder.text('Đang đặt hàng...');
                    btnOrder.prop('disabled', true);
                    createOrder(formData, btnOrder);
                });
            })
        })
    </script>
@endsection
