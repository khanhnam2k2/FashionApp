<!-- Modal Create/Update Post -->
<div class="modal fade" id="updatePostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titlePostModal">Tạo mới bài viết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_post">
                    <input type="hidden" name="postId" id="postId">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Tiêu đề<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titlePost" name="title">
                        </div>
                    </div>
                    <div class="">
                        <label for="postImage" class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control" id="postImage" name="image">
                    </div>
                    <div class="w-100 d-flex justify-content-center my-2" id="imagePostPreviewContainer">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="contentPost" class="form-label">Nội dung</label>
                            <textarea name="content" id="editor"></textarea>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Trạng thái<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusPost">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnSubmitPost" type="button" class="btn btn-primary">Lưu
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    .ck-editor__editable_inline {
        height: 350px;
    }

    #updatePostModal {
        --bs-modal-width: 1000px !important;
    }

    #imagePostPreview {
        width: 400px;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
<script type="text/javascript">
    /**
     * Submit form post
     * @param btn button submit
     */
    function doSubmitPost(btn) {
        const formPost = $('form#form_post');
        let formData = new FormData(formPost[0]);
        formData.append('statusPost', $('#cbStatusPost').is(':checked') ? 1 : 0);
        formData.append('contentPost', myEditor.getData());
        if ($('#postId').val() == '') {
            showConfirmDialog('Bạn có chắc chắn muốn tạo bài viết này?', function() {
                btn.text('Đang tạo...');
                btn.prop('disabled', true);
                createPost(formData, btn, formPost);
            });
        } else {
            showConfirmDialog('Bạn có chắc chắn muốn cập nhật bài viết này?', function() {
                btn.text('Đang cập nhật...');
                btn.prop('disabled', true);
                updatePost(formData, btn, formPost);
            });
        }
    }

    /**
     * Create post
     * @param data data to create post
     * @param btn button create post
     * @param form form create post
     */
    function createPost(data, btn, form) {
        // Remove previous error messages and classes
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        form.find('.text-danger').remove();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.post.create') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Bài viết được tạo thành công');
                searchPost();
                $('#updatePostModal').modal('toggle');
            }
        }).fail(function(xhr) {
            const errors = xhr.responseJSON.errors;
            if (xhr.status === 400 && errors) {
                // Loop through the errors and display them
                $.each(errors, function(key, value) {
                    if (key == 'contentPost') {
                        const contentPost = form.find('.ck-editor');
                        contentPost.after('<div style="font-size:0.875em" class="text-danger">' + value[
                                0] +
                            '</div>');
                    }
                    const inputField = form.find('[name="' + key + '"]');
                    inputField.addClass('is-invalid');
                    inputField.after('<div  class="invalid-feedback">' + value[
                            0] +
                        '</div>');
                });
            } else {
                notiError();
            }
        }).always(function() {
            btn.text('Lưu');
            btn.prop('disabled', false);
        })
    }

    /**
     * Update post
     * @param data data to update post
     * @param btn button update post
     * @param form form update post
     */
    function updatePost(data, btn, form) {
        // Remove previous error messages and classes
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.post.update') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Đã cập nhật bài đăng thành công');
                searchPost();
                $('#updatePostModal').modal('toggle');
            }
        }).fail(function(xhr) {
            const errors = xhr.responseJSON.errors;
            if (xhr.status === 400 && errors) {
                // Loop through the errors and display them
                $.each(errors, function(key, value) {
                    if (key == 'contentPost') {
                        const contentPost = form.find('.ck-editor');
                        contentPost.after('<div style="font-size:0.875em" class="text-danger">' + value[
                                0] +
                            '</div>');
                    }
                    const inputField = form.find('[name="' + key + '"]');
                    inputField.addClass('is-invalid');
                    inputField.after('<div  class="invalid-feedback">' + value[
                            0] +
                        '</div>');
                });
            } else {
                notiError();
            }
        }).always(function() {
            btn.text('Lưu');
            btn.prop('disabled', false);
        })
    }

    $(document).ready(function() {

        // Add/change image for post
        $('#postImage').on('change', function() {
            handleImageUpload(this, $('#imagePostPreview'));
        });

        // Click to submit the post
        $('#btnSubmitPost').click(function(e) {
            e.preventDefault();
            doSubmitPost($(this));
        });

        // Event show post modal
        $('#updatePostModal').on('shown.bs.modal', function(e) {
            $('#titlePost').focus();
            const data = $(e.relatedTarget).data('item');
            let imagePreviewHtml = '';
            if (data) {
                imagePreviewHtml = `<img src="/storage/${data.image}" id="imagePostPreview" />`
                $("#postId").val(data.id);
                $("#titlePost").val(data.title);
                myEditor.setData(data.content);
                $('#imagePostPreviewContainer').html(imagePreviewHtml);
                $('#cbStatusPost').prop('checked', data.status == 1);
                $('#titlePostModal').html('Cập nhật bài viết');
            } else {
                imagePreviewHtml =
                    `<img src="{{ asset('img/default-img.png') }}" id="imagePostPreview" />`;
                $('form#form_post')[0].reset();
                myEditor.setData('');
                $('#imagePostPreviewContainer').html(imagePreviewHtml);
                $('#titlePostModal').html('Tạo mới bài viết');
            }
        });
    })
</script>
