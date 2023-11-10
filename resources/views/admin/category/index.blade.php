@extends('admin.layouts.app')
@section('content')
    <div class="category-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh mục sản phẩm</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchCategory" placeholder="..." class="form-control" name="nameCategory">
                <button class="btn btn-primary" onclick="searchCategory()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal"
                data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>Tạo danh mục
                mới</button>
        </div>
        <div class="mt-3">
            <div id="category_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('admin.category.modal_update')
@endsection
@section('web-script')
    <script>
        const urlDeleteCategory = "{{ route('admin.category.delete', ['id' => ':id']) }}";

        /**
         * Load cagtegory list
         */
        function searchCategory(page = 1) {
            $.ajax({
                url: '<?= route('admin.category.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchCategory').val(),
                }
            }).done(function(data) {
                $('#category_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchCategory();


            // delete category
            $(document).on('click', '#btnDeleteCate', function() {
                let categoryId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa danh mục này không?', function() {
                    $.ajax({
                        url: urlDeleteCategory.replace(':id', categoryId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Xóa danh mục thành công");
                            searchCategory();
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
