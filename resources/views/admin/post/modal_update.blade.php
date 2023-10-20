<!-- Modal Create/Update Post -->
<div class="modal fade" id="updatePostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titlePostModal">Create new post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_post">
                    <input type="hidden" name="postId" id="postId">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="">
                        <label for="postImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="postImage" name="image">
                    </div>
                    <div class="w-100 d-flex justify-content-center my-2" id="imagePostPreviewContainer">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content"></textarea>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Status<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusPost">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button id="btnSaveProduct" type="button" onclick="doSubmitPost()" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    #imagePostPreview {
        width: 200px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
<script>
    /**
     * Submit form post
     */
    function doSubmitPost() {
        let formData = new FormData($('form#form_post')[0]);
        formData.append('statusPost', $('#cbStatusPost').is(':checked') ? 1 : 0)
        if ($('#postId').val() == '') {
            showConfirmDialog('Are you sure you want to create this post?', function() {
                createPost(formData);
            });
        } else {
            showConfirmDialog('Are you sure you want to update this post?', function() {
                updatePost(formData);
            });
        }
    }

    /**
     * Create form post
     */
    function createPost(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('post.create') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Post created successfully');
                searchPost();
                $('#updatePostModal').modal('toggle');
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

    }

    /**
     * Update form post
     */
    function updatePost(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('post.update') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Post updated successfully');
                searchPost();
                $('#updatePostModal').modal('toggle');

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
    }

    $(document).ready(function() {

        // add/change image for product
        $('#postImage').on('change', function() {
            handleImageUpload(this, $('#imagePostPreview'));
        });
        // event show product modal
        $('#updatePostModal').on('shown.bs.modal', function(e) {
            const data = $(e.relatedTarget).data('item');
            let imagePreviewHtml = '';
            if (data) {
                imagePreviewHtml = `<img src="/storage/${data.image}" id="imagePostPreview" />`
                $("#postId").val(data.id);
                $("#title").val(data.title);
                $("#content").val(data.content);
                $('#imagePostPreviewContainer').html(imagePreviewHtml);
                $('#cbStatusPost').prop('checked', data.status == 1);
                $('#titlePostModal').html('Update Post');
            } else {
                imagePreviewHtml =
                    `<img src="{{ asset('img/default-img.png') }}" id="imagePostPreview" />`;
                $("#postId").val('');
                $("#title").val('');
                $("#content").val('');
                $("#postImage").val('');
                $('#imagePostPreviewContainer').html(imagePreviewHtml);
                $('#cbStatusPost').prop('checked', true);
                $('#titlePostModal').html('Create new post');
            }
        });
    })
</script>
