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
                                            <input type="text" class="form-control" name="full_name"
                                                value="{{ Auth::user()->name ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Số liên hệ<span>*</span></p>
                                            <input type="text" class="form-control"
                                                value="{{ Auth::user()->phone ?? '' }}" name="phone">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Địa chỉ Email<span>*</span></p>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ Auth::user()->email ?? '' }}">
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
                                    <div class="d-flex justify-content-between">
                                        <h4 class="order__title">Đơn hàng của bạn</h4>
                                        <a class="text-dark" href="{{ route('cart.index') }}"><i
                                                class="fa-solid fa-bag-shopping mr-2"></i>Giỏ hàng</a>
                                    </div>
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
                                        <li>Tiền ship: <span>0đ</span></li>
                                        <li>Tổng tiền thanh toán: <span
                                                id="total_order">{{ number_format($totalCarts, 0, ',', '.') }}<span><span>đ</span>
                                        </li>
                                    </ul>

                                    <div class="checkout__input__checkbox">
                                        <p for="cod">
                                            <i class="fa-solid fa-truck-fast mr-2"></i>Thanh toán khi giao hàng (COD)
                                        </p>
                                        <p>Male Fashion cảm ơn bạn đã tin tưởng</p>
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
    #form_order .nice-select {
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

            // Call api to render data list city
            callAPI(host + '?depth=1');

            // When city changes, call api to render data list district
            $("#city").change(() => {
                callApiDistrict(host + "p/" + $("#city").find(':selected').data('id') + "?depth=2");
            });

            // When district changes, call api to render data list ward
            $("#district").change(() => {
                callApiWard(host + "d/" + $("#district").find(':selected').data('id') + "?depth=2");
            });

            /**
             * Create new orer
             * @praram {formData} data - data to create new order
             * @praram {Element} btn - button click create order
             */
            function createOrder(data, btn, form) {
                // Remove previous error messages and classes
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
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
                    const errors = xhr.responseJSON.errors;
                    if (xhr.status === 400 && errors) {
                        // Loop through the errors and display them
                        $.each(errors, function(key, value) {
                            const inputField = form.find('[name="' + key + '"]');
                            inputField.addClass('is-invalid');
                            inputField.after('<div class="invalid-feedback">' + value[0] +
                                '</div>');
                        });
                    } else {
                        notiError();
                    }
                }).always(function() {
                    btn.text('Đặt hàng');
                    btn.prop('disabled', false);
                })
            }

            // Handle checkboxes payment method
            $('input[type="checkbox"]').change(function() {
                if ($(this).prop('checked')) {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                }
            });

            // Click to order 
            $('#btn-order').click(function(e) {
                e.preventDefault();
                const btnOrder = $(this);
                const formCreateOrder = $('form#form_order');
                let formData = new FormData(formCreateOrder[0]);
                const spanContent = $("#total_order").text();
                const totalOrder = parseFloat(spanContent.replace(/\./g, ''));
                formData.append('total_order', totalOrder);
                showConfirmDialog('Bạn có chắc chắn muốn đặt đơn hàng này không?', function() {
                    btnOrder.text('Đang đặt hàng...');
                    btnOrder.prop('disabled', true);
                    createOrder(formData, btnOrder, formCreateOrder);
                });
            })
        })
    </script>
@endsection
