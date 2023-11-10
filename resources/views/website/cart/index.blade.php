@extends('layouts.app')
@section('title', 'Giỏ hàng - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Giỏ hàng</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <a href="{{ route('shop.index') }}">Shop</a>
                            <span>Giỏ hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <div id="cart_table"></div>
    <!-- Shopping Cart Section End -->
@endsection
@section('web-script')
    <script>
        /**
         * Load cart
         */
        function searchCart() {
            $.ajax({
                url: "{{ route('cart.search') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data) {
                $('#cart_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        /**
         * Update cart
         * @param data
         */
        function updateCart(data) {
            $.ajax({
                type: "POST",
                url: "{{ route('cart.update') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
            }).done(function(res) {
                const data = res.data.original;
                if (data.success) {
                    notiSuccess(data.success, 'center');
                } else if (data.error) {
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
                searchCart();
            })
        }

        $(document).ready(function() {
            searchCart();

            // Remove product from cart
            $(document).on('click', '.remove-from-cart', function() {
                const productId = $(this).data('product-id');
                const size = $(this).data('size');
                $(this).prop('disabled', true);
                showConfirmDialog('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?', function() {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('cart.remove') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            productId: productId,
                            size: size
                        }
                    }).done(function(res) {
                        const data = res.data.original;
                        if (data.success) {
                            notiSuccess(data.success);
                            searchCart();
                        } else if (data.error) {
                            notiError(data.error);
                        }

                    }).fail(function() {
                        notiError();
                    }).always(function() {
                        $(this).prop('disabled', false);
                    })
                });
            });

            // Increment quantity product from cart
            $(document).on('click', '.increment', function() {
                let input = $(this).siblings(".qtyProductCart");
                let btnDecrement = $(this).siblings(".decrement");
                let currentValue = parseInt(input.val());
                $(this).prop('disabled', true);
                btnDecrement.prop('disabled', true);
                input.prop('disabled', true);
                input.val(currentValue + 1);
                const productId = input.data('product-id');
                const size = input.data('size');
                const newQuantity = parseInt(input.val());
                const data = {
                    productId: productId,
                    size: size,
                    quantity: newQuantity,
                }
                updateCart(data);
            })

            // Decrement quantity product from cart
            $(document).on('click', '.decrement', function() {
                let input = $(this).siblings(".qtyProductCart");
                let btnIncrement = $(this).siblings(".increment");
                let currentValue = parseInt(input.val());
                if (currentValue > 1) {
                    $(this).prop('disabled', true);
                    btnIncrement.prop('disabled', true);
                    input.prop('disabled', true);
                    input.val(currentValue - 1);
                    const productId = input.data('product-id');
                    const size = input.data('size');
                    const newQuantity = parseInt(input.val());
                    const data = {
                        productId: productId,
                        size: size,
                        quantity: newQuantity,
                    }
                    updateCart(data);
                } else {
                    notiError('Số lượng tối thiểu là 1');
                }

            })
        })
    </script>
@endsection
