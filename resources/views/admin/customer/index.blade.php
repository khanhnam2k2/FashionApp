@extends('admin.layouts.app')
@section('content')
    <div class="customer-container container">
        <div class="d-flex justify-content-between">
            <h2>Customer List</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchCustomer" placeholder="search here..." class="form-control"
                    name="nameCategory">
                <button class="btn btn-primary" onclick="searchCustomer()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
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
        function searchCustomer(page = 1) {
            $.ajax({
                url: '<?= route('admin.customer.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchCustomer').val(),
                }
            }).done(function(data) {
                $('#customer_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchCustomer();


            // delete customer 
            $(document).on('click', '#btnDeleteCustomer', function() {
                let customerId = $(this).data('id');
                showConfirmDialog('Are you sure you want to delete this customer?', function() {
                    $.ajax({
                        url: urlDeleteCustomer.replace(':id', customerId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Deleted customer successfully");
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
