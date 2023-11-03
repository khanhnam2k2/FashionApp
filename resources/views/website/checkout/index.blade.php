@extends('layouts.app')
@section('title', 'Checkout - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Check Out</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop.index') }}">Shop</a>
                            <span>Check Out</span>
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
                                <h6 class="checkout__title">Billing Details</h6>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="checkout__input">
                                            <p>Full Name<span>*</span></p>
                                            <input type="text" name="full_name" value="{{ Auth::user()->name ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Phone<span>*</span></p>
                                            <input type="text" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Email<span>*</span></p>
                                            <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__input mb-2">
                                    <p>Address<span>*</span></p>
                                    <textarea name="address" placeholder="Street Address" class="form-control"></textarea>
                                </div>
                                <div class="checkout__input">
                                    <p>Message</p>
                                    <textarea class="form-control" name="message" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="checkout__order">
                                    <h4 class="order__title">Your order</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
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
                                                        ${{ $item->productPrice }}
                                                    </td>
                                                    <td>
                                                        $<span>{{ $item->total }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <ul class="checkout__total__all">
                                        <li>Total amount <span>$<span id="total_order">{{ $totalCarts }}</span></span>
                                        </li>
                                    </ul>
                                    <p> <i class="fa-solid fa-truck-fast"></i> Free ship</p>
                                    <button type="button" id="btn-order" class="site-btn">PLACE ORDER</button>
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
                btnOrder.prop('disabled', true);
                let formData = new FormData($('form#form_order')[0]);
                formData.append('total_order', parseFloat($('#total_order').text()));
                showConfirmDialog('Are you sure you want to place this order?', function() {
                    createOrder(formData, btnOrder);
                });
            })
        })
    </script>
@endsection
