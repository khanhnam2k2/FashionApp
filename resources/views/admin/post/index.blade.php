@extends('admin.layouts.app')
@section('content')
    <div class="product-container ">
        <div class="d-flex justify-content-between">
            <h2>Danh sách bài viết</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchPost" placeholder="Tìm kiếm ở đây..." class="form-control" name="namePost">
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePostModal" data-bs-backdrop="static"
                data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>Tạo mới bài
                viết</button>
        </div>
        <div class="mt-3">
            <div id="post_table">

            </div>
        </div>
    </div>
    @include('admin.post.modal_update')
@endsection
@section('web-script')
    <script>
        // Use Ckeditor
        var myEditor;
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}"
                }
            }).then(editor => {
                myEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        const urlDeletePost = "{{ route('admin.post.delete', ['id' => ':id']) }}";
        const iconLoadingPost = "{{ asset('admin/assets/images/loading.svg') }}";

        /**
         * Load cagtegory list
         * @param page current page
         * @param searchName name of the search
         */
        function searchPost(page = 1, searchName = '') {
            $('#post_table').html(`<div class="d-flex justify-content-center mt-5">
                                <img src="${iconLoadingPost}" alt="">
                            </div>`);
            $.ajax({
                url: '<?= route('admin.post.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
                },
            }).done(function(data) {
                $('#post_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {

            searchPost();

            // Event enter keyword search
            $('#txtSearchPost').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchPost(1, search);
                } else {
                    searchPost();
                }
            }, 500));

            // Delete product
            $(document).on('click', '#btnDeletePost', function() {
                let postId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa bài viết này?', function() {
                    $.ajax({
                        url: urlDeletePost.replace(':id', postId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Đã xóa bài viết thành công");
                            searchPost();
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

            // Update status category
            $(document).on('change', '.cbPostStatus', function() {
                $.ajax({
                    type: "PUT",
                    url: "{{ route('admin.post.updateStatus') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        postId: this.value,
                        status: this.checked ? 1 : 0,
                    }
                }).done(function(res) {
                    if (res == 'ok') {
                        notiSuccess('Cập nhật trạng thái thành công');
                    } else {
                        notiError('Cập nhật không thành công');
                    }
                }).fail(function() {
                    notiError('Cập nhật không thành công');
                })
            });
        });
    </script>
@endsection
