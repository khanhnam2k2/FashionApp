@extends('admin.layouts.app')
@section('content')
    <div class="category-container container">
        <div class="d-flex justify-content-between">
            <h2>Category list</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal"
                data-bs-backdrop="static" data-bs-keyboard="false">New Category</button>
        </div>
        <div class="form-group">
            <div id="category_table"></div>
        </div>
    </div>
    @include('admin.category.modal_update')
@endsection
@section('web-script')
    <script>
        const urlDeletePost = "{{ route('category.delete', ['id' => ':id']) }}";

        function searchCategory(page = 1) {
            $.ajax({
                url: '<?= route('category.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data) {
                $('#category_table').html(data);
            }).fail(function() {
                console.log();
            });
        }
        $(document).ready(function() {
            searchCategory();
            $(document).on('click', '#btnDeleteCate', function() {
                let categoryId = $(this).data('id');
                showConfirmDialog('Are you sure you want to delete this category?', function() {
                    $.ajax({
                        url: urlDeletePost.replace(':id', categoryId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Deleted category successfully");
                            searchCategory();
                        }
                    }).fail(function(xhr) {
                        if (xhr.status === 400 && xhr.responseJSON.errors) {
                            const errorMessages = xhr.responseJSON.errors;
                            for (let fieldName in errorMessages) {
                                notiError(errorMessages[fieldName][0]);
                            }
                        } else {
                            notiError('Something went wrong. Please try again');
                        }
                    })
                })
            })

        });
    </script>
@endsection
