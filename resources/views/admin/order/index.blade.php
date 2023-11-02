@extends('admin.layouts.app')
@section('content')
    <div class="order-container container">
        <div class="d-flex justify-content-between">
            <h2>Order List</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchOrder" placeholder="search here..." class="form-control" name="nameCategory">
                <button class="btn btn-primary" onclick="searchOrderAdmin()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal"
                data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>New
                Category</button> --}}
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
        function searchOrderAdmin(page = 1) {
            $.ajax({
                url: '<?= route('admin.order.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchOrder').val(),
                }
            }).done(function(data) {
                $('#order_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchOrderAdmin();


            // delete category
            $(document).on('click', '#btnDeleteOrder', function() {
                let orderId = $(this).data('id');
                showConfirmDialog('Are you sure you want to delete this order?', function() {
                    $.ajax({
                        url: urlDeleteOrder.replace(':id', orderId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Deleted order successfully");
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
