@extends('admin.layouts.app')
@section('content')
    <div class="orderdetails-container container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Danh sách chi tiết đơn hàng</h2>
            <span id="orderId" class="d-none">{{ $id }}</span>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchOrderDetails" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="orderDeitals">
            </div>
            <div class="">
                <a href="{{ route('admin.order.index') }}" class="btn btn-danger"><i
                        class="fa-solid fa-circle-arrow-left me-2"></i>Quay lại</a>
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
         * @param page - current page
         * @param searchName - name of the search
         */
        function searchOrderDetailsAdmin(page = 1, searchName = '') {
            $.ajax({
                url: '<?= route('admin.order.searchDetails') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    orderId: $("#orderId").text(),
                    searchName: searchName,
                }
            }).done(function(data) {
                $('#order_details_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchOrderDetailsAdmin();

            // Event enter keyword search
            $('#txtSearchOrderDetails').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchOrderDetailsAdmin(1, search);
                } else {
                    searchOrderDetailsAdmin();
                }
            }, 500));

        });
    </script>
@endsection
