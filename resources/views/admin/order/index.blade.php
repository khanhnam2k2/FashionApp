@extends('admin.layouts.app')
@section('content')
    <div class="order-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh sách đặt hàng</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchOrder" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="nameCategory">
            </div>
        </div>
        <div class="mt-3">
            <div id="order_table">
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
        const urlDeleteOrder = "{{ route('admin.order.delete', ['id' => ':id']) }}";

        /**
         * Load cagtegory list
         */
        function searchOrderAdmin(page = 1, searchName = '') {
            $.ajax({
                url: '<?= route('admin.order.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
                }
            }).done(function(data) {
                $('#order_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchOrderAdmin();

            // event enter keyword search
            $('#txtSearchOrder').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchOrderAdmin(1, search);
                } else {
                    searchOrderAdmin();
                }
            }, 500));

            // delete category
            $(document).on('click', '#btnDeleteOrder', function() {
                let orderId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa đơn hàng này không?', function() {
                    $.ajax({
                        url: urlDeleteOrder.replace(':id', orderId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Xóa đơn hàng thành công");
                            searchOrderAdmin();
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
                    })
                })
            })

        });
    </script>
@endsection
