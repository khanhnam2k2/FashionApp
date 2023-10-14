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
                            <a href="./index.html">Home</a>
                            <a href="./shop.html">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
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
        $(document).ready(function() {
            searchCart();
            $(document).on('click', '.remove-from-cart', function() {
                const productId = $(this).data('product-id');
                const size = $(this).data('size');
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
                        if (res == 'ok') {
                            notiSuccess('Successfully removed the product from the cart');
                            searchCart();
                        }

                    }).fail(function() {

                    })
                });
            })

        })
    </script>
@endsection
