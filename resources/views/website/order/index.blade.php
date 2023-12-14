@extends('layouts.app')
@php
    use App\Enums\StatusOrder;
@endphp
@section('title', 'Đơn mua - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Đơn mua</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <span>Đơn mua</span>
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
                                <a class="{{ request()->is('profile') ? 'active' : '' }}"
                                    href="{{ route('profile.index') }}"><i class="fa-regular fa-user mr-2"></i>Trang cá
                                    nhân</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('order') ? 'active' : '' }}" href="{{ route('order.index') }}"><i
                                        class="fa-solid fa-receipt mr-2"></i>Đơn mua</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8">
                    <nav class="" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                        <div class="nav nav-tabs d-flex justify-content-between align-items-center mb-3" id="nav-tab"
                            role="tablist">
                            <button class="btn-show-order nav-link active border-0" type="button">Tất cả</button>
                            <button class="btn-show-order nav-link border-0" data-status="1" type="button">Chờ xác
                                nhận</button>
                            <button class="btn-show-order nav-link border-0" data-status="2" type="button">Xác nhận thành
                                công</button>
                            <button class="btn-show-order nav-link border-0"data-status="3" type="button">Đang
                                giao</button>
                            <button class="btn-show-order nav-link border-0"data-status="4" type="button">Hoàn
                                thành</button>
                            <button class="btn-show-order nav-link border-0"data-status="0" type="button">Đã hủy</button>

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

@section('web-script')
    <script>
        var statusCancelOrder = {{ StatusOrder::cancelOrder }};
        var statusWatingCofirm = {{ StatusOrder::orderPlaced }};

        /**
         * Load order list
         * @param page - current page number
         * @param statusOrder - status order
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

        /**
         * Load order details list
         * @param page - current page number
         * @param orderId - id of order
         */
        function searchOrderDetails(page = 1, orderId = null, btn) {
            $.ajax({
                url: '<?= route('order.searchDetails') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    orderId: orderId,
                    paginate: null,
                }
            }).done(function(data) {
                $('#orderDetails_' + orderId).html(data);
            }).fail(function() {
                notiError();
            }).always(function() {
                btn.hide();
            });
        }

        /**
         * Update status order
         * @param orderId - id of order
         * @param status - status order
         * @param btn - button to update status
         */
        function updateStatusOrder(orderId, status, btn, message = null) {
            $.ajax({
                type: "POST",
                url: "{{ route('order.updateStatus') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    orderId: orderId,
                    status: status,
                },
            }).done(function(res) {
                const data = res.data.original;
                if (data.success) {
                    notiSuccess(message ?? data.success, 'center', function() {
                        window.location.reload();
                    });
                } else {
                    notiError(data.error);
                }
            }).fail(function() {
                notiError();
            });
        }
        $(document).ready(function() {
            searchOrder();

            // Show order
            $('.btn-show-order').on('click', function(e) {
                e.preventDefault();
                $('.btn-show-order').removeClass('active');
                $(this).addClass('active');
                const statusOrder = $(this).data('status');
                searchOrder(1, statusOrder);
            });

            // Show order details
            $(document).on('click', '.btn-order-details', function(e) {
                e.preventDefault();
                const orderId = $(this).data('order-id');
                searchOrderDetails(1, orderId, $(this));
            });

            // Click to cancel order
            $(document).on('click', '.btn-cancel-order', function(e) {
                e.preventDefault();
                const orderId = $(this).data('order-id');
                const btnCancel = $(this);
                const messageSuccess = 'Đơn hàng đã được hủy thành công!'
                showConfirmDialog('Bạn có chắc chắn muốn hủy đơn hàng này không?', function() {
                    btnCancel.prop('disabled', true);
                    btnCancel.text('Đang hủy...');
                    updateStatusOrder(orderId, parseInt(statusCancelOrder), btnCancel,
                        messageSuccess);
                });
            });

            // Click to repurchase order
            $(document).on('click', '.btn-repurchase', function(e) {
                e.preventDefault();
                const orderId = $(this).data('order-id');
                const btnRepurchase = $(this);
                const messageSuccess =
                    'Đã mua lại đơn hàng thành công! Vui lòng kiểm tra lại đơn mua của bạn'
                showConfirmDialog('Bạn có chắc chắn muốn mua lại đơn đặt hàng này không?', function() {
                    btnRepurchase.prop('disabled', true);
                    btnRepurchase.text('Đang mua lại...');
                    updateStatusOrder(orderId, parseInt(statusWatingCofirm), btnRepurchase,
                        messageSuccess);
                });
            });

        })
    </script>
@endsection
