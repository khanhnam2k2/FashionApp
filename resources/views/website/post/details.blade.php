@extends('layouts.app')
@section('title', $post->title . ' - Male Fashion')
@section('content')
    <!-- Blog Details Hero Begin -->
    <section class="blog-hero spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 text-center">
                    <div class="blog__hero__text">
                        <h2>{{ $post->title }}</h2>
                        <ul>
                            <li><i class="fa-solid fa-user mr-2"></i> {{ $post->author }}</li>
                            <li><i class="fa-solid fa-calendar mr-2"></i>{{ $post->created_at->format('d F Y') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <div class="blog__details__pic">
                        <img src="{{ Storage::url($post->image) }}" alt="">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog__details__content">
                        <div class="blog__details__text">
                            {!! $post->content !!}
                        </div>

                        <div class="blog__details__option mb-3">
                        </div>
                        <div class="">
                            <div class="comment-container" id="commentPostList">
                            </div>
                        </div>
                        <div class="blog__details__comment">
                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modalCommentPost">
                                Để lại bình luận của bạn
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('website.post.modelComment')
@endsection

@section('web-script')
    <script>
        const urlDeleteComment = "{{ route('comment.delete', ['id' => ':id']) }}";

        function showFile(input) {
            if (input.files && input.files[0]) {
                let file = input.files[0];
                let fileCommentHtml = '';
                if (file.type.startsWith("image/")) {
                    fileCommentHtml = `<img src="${URL.createObjectURL(file)}"  alt="Image Comment" />`;
                } else if (file.type.startsWith("video/")) {
                    fileCommentHtml = `<video src="${URL.createObjectURL(file)}" controls></video>`;
                }
                $('#previewFileCommentPost').html(fileCommentHtml);
                $('#deleteFileCommentPost').show();
            }
        }

        function deleteFileComment(btn) {
            $('#file').val('');
            $('#previewFileCommentPost').empty();
            $('input[name="fileOld"]').val('');
            btn.hide();
        }

        function searchCommentPost(page = 1) {
            $.ajax({
                url: '<?= route('comment.searchCommentPost') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    typeComment: 'post',
                }
            }).done(function(data) {
                $('#commentPostList').html(data);
            }).fail(function(xhr) {
                notiError();
            });
        }

        function createComment(data, btn, form) {
            $.ajax({
                type: "POST",
                url: "{{ route('comment.create') }}",
                contentType: false,
                processData: false,
                data: data,
            }).done(function(res) {
                if (res.data == null) {
                    notiError('Vui lòng nhập nội dung bình luận');
                    return;
                }
                notiSuccess('Bình luận thành công', 'center');
                form[0].reset();
                deleteFileComment($('#deleteFileCommentPost'));
                $('#modalCommentPost').modal('toggle');
                searchCommentPost();
            }).fail(function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "/login";
                } else if (xhr.status === 400 && xhr.responseJSON.errors) {
                    const errorMessages = xhr.responseJSON.errors;
                    for (let fieldName in errorMessages) {
                        notiError(errorMessages[fieldName][0]);
                    }
                } else {
                    notiError();
                }
            }).always(function() {
                btn.prop('disabled', false);
            })
        }

        function updateComment(data, btn, form) {
            $.ajax({
                type: "POST",
                url: "{{ route('comment.update') }}",
                contentType: false,
                processData: false,
                data: data,
            }).done(function(res) {
                if (res.data == null) {
                    notiError('Vui lòng nhập nội dung bình luận');
                    return;
                } else {
                    const data = res.data.original;
                    if (data.error) {
                        notiError(data.error);
                        return;
                    }
                }
                notiSuccess('Cập nhật bình luận thành công', 'center');
                form[0].reset();
                deleteFileComment($('#deleteFileCommentPost'));
                $('#modalCommentPost').modal('toggle');
                searchCommentPost();
            }).fail(function(xhr) {
                if (xhr.status === 400 && xhr.responseJSON.errors) {
                    const errorMessages = xhr.responseJSON.errors;
                    for (let fieldName in errorMessages) {
                        notiError(errorMessages[fieldName][0]);
                    }
                } else {
                    notiError();
                }
            }).always(function() {
                btn.prop('disabled', false);
            })
        }

        $(document).ready(function() {
            searchCommentPost();


            // create comment post
            $('#btn-comment-post').click(function(e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                const form = $('form#form_comment_post');
                let formData = new FormData(form[0]);
                const commentId = $('#commentId').val();
                if (commentId == '') {
                    createComment(formData, $(this), form);
                } else {
                    updateComment(formData, $(this), form);
                }
            });

            // chang file comment post
            $('#file').change(function() {
                showFile(this);
            });

            // delete file comment post
            $('#deleteFileCommentPost').click(function() {
                deleteFileComment($(this))
            });

            $('#modalCommentPost').on('shown.bs.modal', function(e) {
                const data = $(e.relatedTarget).data('item');
                let filePreviewHtml = '';
                if (data) {
                    const fileData = JSON.parse(data.file);
                    $("input[name='commentId']").val(data.id);
                    $("textarea[name='content']").val(data.content);
                    $("input[name='fileOld']").val(data.file);
                    if (fileData) {
                        $('#deleteFileCommentPost').show();
                        if (fileData.type.startsWith('image/'))
                            filePreviewHtml = `<img src="/storage/${fileData.path}" />`;
                        else if (fileData.type.startsWith('video/')) {
                            filePreviewHtml =
                                `<video src="/storage/${fileData.path}" controls />`;
                        }
                    }
                    $('#previewFileCommentPost').html(filePreviewHtml);
                    $('#titleComment').html('Chỉnh sửa bình luận');
                } else {
                    $("input[name='commentId']").val('');
                    $("textarea[name='content']").val('');
                    $('#previewFileCommentPost').empty();
                    $("input[name='fileOld']").val('');
                    $('#deleteFileCommentPost').hide();
                    $('#titleComment').html('Bình luận');
                }
            });

            // delete comment
            $(document).on('click', '.delete-comment-post', function() {
                let commentId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xoá bình luận này không?', function() {
                    $.ajax({
                        url: urlDeleteComment.replace(':id', commentId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        const data = res.data.original;
                        if (data.success) {
                            notiSuccess(data.success);
                            searchCommentPost();
                        } else {
                            notiError(data.error);
                            return;
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
        })
    </script>
@endsection
