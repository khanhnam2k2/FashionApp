@extends('admin.layouts.app')
@section('content')
    <div class="customer-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh sách tài khoản khách hàng</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchCustomer" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="nameCategory">
            </div>
        </div>
        <div class="mt-3">
            <div id="customer_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('web-script')
    <script>
        const urlDeleteCustomer = "{{ route('admin.customer.delete', ['id' => ':id']) }}";

        /**
         * Load customer list
         * @param page 
         */
        function searchCustomer(page = 1, searchName = '') {
            $.ajax({
                url: '<?= route('admin.customer.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
                }
            }).done(function(data) {
                $('#customer_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchCustomer();

            // event enter keyword search
            $('#txtSearchCustomer').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchCustomer(1, search);
                } else {
                    searchCustomer();
                }
            }, 500));

            // delete customer 
            $(document).on('click', '#btnDeleteCustomer', function() {
                let customerId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa tài khoản khách hàng này?', function() {
                    $.ajax({
                        url: urlDeleteCustomer.replace(':id', customerId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Đã xóa tài khoản khách hàng thành công");
                            searchCustomer();
                        }
                    }).fail(function() {
                        notiError();
                    })
                })
            })
        });
    </script>
@endsection
