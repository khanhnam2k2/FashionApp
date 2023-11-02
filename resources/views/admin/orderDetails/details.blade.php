@extends('admin.layouts.app')
@section('content')
    <div class="orderdetails-container container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detail Order List</h2>
            <span id="orderId" class="d-none">{{ $id }}</span>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchOrderDetails" placeholder="search here..." class="form-control"
                    name="orderDeitals">
                <button class="btn btn-primary" onclick="searchOrderDetailsAdmin()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
        <div class="mt-3">
            <div id="order_details_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('admin.order.modal_update_status')
@endsection
@section('web-script')
    <script>
        /**
         * Load order details list
         */
        function searchOrderDetailsAdmin(page = 1) {
            $.ajax({
                url: '<?= route('admin.order.searchDetails') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    orderId: $("#orderId").text(),
                    searchName: $('#txtSearchOrderDetails').val(),
                }
            }).done(function(data) {
                $('#order_details_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchOrderDetailsAdmin();
        });
    </script>
@endsection
