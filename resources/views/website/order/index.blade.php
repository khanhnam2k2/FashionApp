@extends('layouts.app')
@section('title', 'My Order - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>My Order</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>My Order</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- About Section Begin -->
    <section class="spad">
        <div class="container order-container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4">
                    <div class="profile">
                        <h4>{{ Auth::user()->name }}</h4>
                        <ul>
                            <li>
                                <a href="#"><i class="fa-regular fa-user mr-2"></i>My profile</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('order') ? 'active' : '' }}" href="{{ route('order.index') }}"><i
                                        class="fa-solid fa-receipt mr-2"></i>My order</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8">
                    <nav class="" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                        <div class="nav nav-tabs d-flex justify-content-between align-items-center mb-3" id="nav-tab"
                            role="tablist">
                            <button class="btn-show-order nav-link active border-0" type="button">All</button>
                            <button class="btn-show-order nav-link border-0" data-status="1" type="button">Wait for
                                confirmation</button>
                            <button class="btn-show-order nav-link border-0" data-status="2" type="button">Confirmed
                                successfully</button>
                            <button class="btn-show-order nav-link border-0"data-status="3"
                                type="button">Delivering</button>
                            <button class="btn-show-order nav-link border-0"data-status="4" type="button">Complete</button>
                            <button class="btn-show-order nav-link border-0"data-status="0" type="button">Cancel
                                Order</button>

                        </div>
                    </nav>
                    <div class="tab-content w-100" id="order_list">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->


@endsection
<style>
    .order-container ul {
        list-style: none;
        margin: 20px 10px;
    }

    .order-container ul li {
        margin-bottom: 10px;
    }

    .order-container ul li a {
        color: #000;
    }

    .order-container ul li a.active {
        color: red;
    }

    .order-container .nav-link {
        background-color: transparent;
        border: none;
        font-size: 20px !important;
    }

    .order-container .nav-link.active {
        color: red !important;
        border-bottom: 1px solid red !important;
    }
</style>
@section('web-script')
    <script>
        /**
         * Load order list
         */
        function searchOrder(page = 1, statusOrder = null) {
            $.ajax({
                url: '<?= route('order.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    statusOrder: statusOrder
                }
            }).done(function(data) {
                $('#order_list').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchOrder();

            $('.btn-show-order').on('click', function(e) {
                e.preventDefault();
                $('.btn-show-order').removeClass('active');
                $(this).addClass('active');
                const statusOrder = $(this).data('status');
                searchOrder(1, statusOrder);
            })
        })
    </script>
@endsection
