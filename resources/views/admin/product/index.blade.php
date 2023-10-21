@extends('admin.layouts.app')
@section('content')
    <div class="product-container ">
        <div class="d-flex justify-content-between">
            <h2>Product List</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchProduct" placeholder="search here..." class="form-control" name="nameProduct">
                <button class="btn btn-primary" onclick="searchProduct()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            @if (count($categories) > 0)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProductModal"
                    data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>New
                    Product</button>
            @else
                <a href="{{ route('admin.category.index') }}">Please create a new category to add products</a>
            @endif
        </div>
        <div class="mt-3">
            <div id="product_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('admin.product.modal_update')
@endsection
@section('web-script')
    <script>
        const urlDeleteProduct = "{{ route('admin.product.delete', ['id' => ':id']) }}";

        /**
         * Load cagtegory list
         */
        function searchProduct(page = 1) {
            $.ajax({
                url: '<?= route('admin.product.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchProduct').val(),
                },
            }).done(function(data) {
                $('#product_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchProduct();


            // Delete product
            $(document).on('click', '#btnDeleteProduct', function() {
                let productId = $(this).data('id');
                showConfirmDialog('Are you sure you want to delete this product?', function() {
                    $.ajax({
                        url: urlDeleteProduct.replace(':id', productId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Deleted product successfully");
                            searchProduct();
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
            });



        });
    </script>
@endsection
