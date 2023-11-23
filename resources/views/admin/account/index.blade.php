@extends('admin.layouts.app')
@section('content')
    <div class="customer-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh sách tài khoản</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchCustomer" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="nameCategory">
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAccountModal"
                    data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>Tạo tài khoản
                    mới</button>
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
    @include('admin.account.create_account_modal')
@endsection
@section('web-script')
    <script>
        const urlDeleteCustomer = "{{ route('admin.account.delete', ['id' => ':id']) }}";

        /**
         * Load customer list
         * @param page 
         */
        function searchCustomer(page = 1, searchName = '') {
            $.ajax({
                url: '<?= route('admin.account.search') ?>?page=' + page,
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
                showConfirmDialog('Bạn có chắc chắn muốn xóa tài khoản này?', function() {
                    $.ajax({
                        url: urlDeleteCustomer.replace(':id', customerId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Đã xóa tài khoản thành công");
                            searchCustomer();
                        }
                    }).fail(function() {
                        notiError();
                    })
                })
            });

            $(document).on('click', '.btnUpdateToAdmin', function(e) {
                e.preventDefault();
                let accountId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn nâng cấp tài khoản này lên admin không?',
                    function() {
                        $.ajax({
                            url: "{{ route('admin.account.updateToAdmin') }}",
                            type: "PUT",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                accountId: accountId,
                            }
                        }).done(function(res) {
                            const data = res.data.original;
                            if (data.success) {
                                notiSuccess(data.success);
                                searchCustomer();
                            } else {
                                notiError(data.error);
                            }
                        }).fail(function() {
                            notiError();
                        })
                    })
            })
        });
    </script>
@endsection
