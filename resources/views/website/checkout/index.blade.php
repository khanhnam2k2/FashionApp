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
                                            <input type="text" value="{{ Auth::user()->phone ?? '' }}" name="phone">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Địa chỉ Email<span>*</span></p>
                                            <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}">
                                        </div>
                                    </div>

                                </div>
                                <p>Địa chỉ giao hàng<span>*</span></p>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <select id="city" name="city" class="form-control">
                                            <option value="" selected>Chọn tỉnh thành</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <select id="district" name="district" class="form-control">
                                            <option value="" selected>Chọn quận huyện</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <select id="ward" name="ward" class="form-control">
                                            <option value="" selected>Chọn phường xã</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="checkout__input mb-2">
                                    <p>Địa chỉ chi tiết<span>*</span></p>
                                    <textarea name="address_details" style="color:#000" class="form-control">{{ Auth::user()->address ?? '' }}</textarea>
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
                                    <div class="checkout__input__checkbox">
                                        <label for="paypal">
                                            Thanh toán bằng ví VnPay
                                            <input type="checkbox" name="vnpay" id="paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="cod">
                                            Thanh toán khi nhận hàng
                                            <input type="checkbox" checked name="cod" id="cod">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <button type="button" id="btn-order" class="site-btn">Đặt hàng</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center">
                    <h2>Giỏ hàng của bạn đang trống! Vui lòng kiểm tra lại giỏ hàng của bạn.</h2>
                </div>
            @endif

        </div>
    </section>
    <!-- Checkout Section End -->
@endsection
<style>
    .nice-select {
        display: none !important;

    }

    #city,
    #district,
    #ward {
        display: block !important;
    }
</style>
@section('web-script')
    <script>
        $(document).ready(function() {
            const host = "https://provinces.open-api.vn/api/";

            /**
             * Call api list city viet nam
             * @param api 
             * */
            function callAPI(api) {
                $.get(api, function(data) {
                    renderData(data, "city");
                })
            }

            callAPI(host + '?depth=1');

            /**
             * Call api list district 
             * @param api 
             * */
            function callApiDistrict(api) {
                $.get(api, function(data) {
                    renderData(data.districts, "district");
                })
            }

            /**
             * Call api list ward 
             * @param api 
             * */
            function callApiWard(api) {
                $.get(api, function(data) {
                    renderData(data.wards, "ward");
                })
            }

            /**
             * Render data html address
             * @param array data address 
             * @param string name element select
             * */
            function renderData(array, select) {
                let row = '<option disable value="">Chọn</option>';
                $.each(array, function(index, element) {
                    row +=
                        `<option data-id="${element.code}" value="${element.name}">${element.name}</option>`;
                });
                $("#" + select).html(row);
            }

            $("#city").change(() => {
                callApiDistrict(host + "p/" + $("#city").find(':selected').data('id') + "?depth=2");
            });

            $("#district").change(() => {
                callApiWard(host + "d/" + $("#district").find(':selected').data('id') + "?depth=2");
            });


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
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else if (data.success) {
                        notiSuccess(data.success, 'center', function() {
                            window.location.href = "{{ route('order.index') }}";
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
            //Handle checkboxes payment method
            $('input[type="checkbox"]').change(function() {
                if ($(this).prop('checked')) {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                }
            });

            // click to order 
            $('#btn-order').click(function(e) {
                e.preventDefault();
                const btnOrder = $(this);
                let formData = new FormData($('form#form_order')[0]);
                formData.append('paymet_method', $('#paypal').is(':checked') ? 1 : 0);
                const spanContent = $("#total_order").text();
                const totalOrder = parseFloat(spanContent.replace(/\./g, ''));
                formData.append('total_order', totalOrder);
                if ($('#paypal').prop('checked')) {
                    showConfirmDialog('Bạn có chắc chắn muốn thanh toán bằng ví VnPay không?', function() {
                        btnOrder.text('Đang tiến hành...');
                        btnOrder.prop('disabled', true);
                        createOrder(formData, btnOrder);
                    });
                } else {
                    showConfirmDialog('Bạn có chắc chắn muốn đặt đơn hàng này không?', function() {
                        btnOrder.text('Đang đặt hàng...');
                        btnOrder.prop('disabled', true);
                        createOrder(formData, btnOrder);
                    });
                }
            })
        })
    </script>
@endsection
