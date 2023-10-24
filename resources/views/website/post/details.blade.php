@extends('layouts.app')
@section('title', 'Post Details - Male Fashion')
@section('content')
    <!-- Blog Details Hero Begin -->
    <section class="blog-hero spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 text-center">
                    <div class="blog__hero__text">
                        <h2>{{ $post->title }}</h2>
                        <ul>
                            <li>By {{ $post->author }}</li>
                            <li>{{ $post->created_at->format('d F Y') }}</li>
                            <li>8 Comments</li>
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

                        <div class="blog__details__option">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="blog__details__author">
                                        {{-- <div class="blog__details__author__pic">
                                            <img src="img/blog/details/blog-author.jpg" alt="">
                                        </div> --}}
                                        <div class="blog__details__author__text">
                                            <h5>Author: {{ $post->author }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment-container" id="commentPostList">

                        </div>
                        <div class="blog__details__comment">
                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modalCommentPost">
                                Leave A Comment
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
            }).fail(function() {
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
                    notiError('Please enter comment content');
                    return;
                }
                notiSuccess('Comment successfully', 'center');
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

        function updateComment(data, btn, form) {
            $.ajax({
                type: "POST",
                url: "{{ route('comment.update') }}",
                contentType: false,
                processData: false,
                data: data,
            }).done(function(res) {
                if (res.data == null) {
                    notiError('Please enter comment content');
                    return;
                } else {
                    const data = res.data.original;
                    if (data.error) {
                        notiError(data.error);
                        return;
                    }
                }
                notiSuccess('Comment successfully', 'center');
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
                    $('#titleComment').html('Edit Comment');
                } else {
                    $("input[name='commentId']").val('');
                    $("textarea[name='content']").val('');
                    $('#previewFileCommentPost').empty();
                    $("input[name='fileOld']").val('');
                    $('#deleteFileCommentPost').hide();
                    $('#titleComment').html('Leave A Comment');
                }
            })


        })
    </script>
@endsection
