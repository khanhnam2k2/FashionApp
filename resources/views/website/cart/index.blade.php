@extends('layouts.app')
@section('title', 'Cart - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shopping Cart</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop.index') }}">Shop</a>
                            <span>Shopping Cart</span>
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
                    searchCart();
                } else if (data.error) {
                    notiError(data.error);
                }
            }).fail(function() {
                notiError();
            })
        }
        $(document).ready(function() {
            searchCart();

            // remove product from cart
            $(document).on('click', '.remove-from-cart', function() {
                const productId = $(this).data('product-id');
                const size = $(this).data('size');
                $(this).prop('disabled', true);
                showConfirmDialog('Are you sure you want to remove this product?', function() {
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

            $(document).on('change', '.qtyProductCart', function() {
                const productId = $(this).data('product-id');
                const size = $(this).data('size');
                const newQuantity = parseInt($(this).val());
                const data = {
                    productId: productId,
                    size: size,
                    quantity: newQuantity,
                }
                updateCart(data);
            })

        })
    </script>
@endsection
