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
                            <h4>Leave A Comment</h4>
                            <form id="form_comment">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="commentType" value="post">
                                    <input type="hidden" name="postId" value="{{ $post->id }}">
                                    <div class="col-lg-12 border">
                                        <textarea name="content" placeholder="Comment"></textarea>
                                        <label for="file">📸</label>
                                        <input type="file" class="form-control d-none" id="file" name="file">
                                    </div>
                                    <div class="position-relative mt-2">
                                        <div id="previewFileComment">
                                        </div>
                                        <span id="deleteFileComment" style="display: none;cursor:pointer"><i
                                                class="fa fa-close"></i></span>
                                    </div>
                                    <div class="col-lg-12 text-center mt-3">
                                        <button id="btn-comment-post" class="site-btn">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
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
                $('#previewFileComment').html(fileCommentHtml);
                $('#deleteFileComment').show();
            }
        }

        function deleteFileComment(btn) {
            $('#file').val('');
            $('#previewFileComment').empty();
            btn.hide();
        }

        function searchCommentPost(page = 1) {
            $.ajax({
                url: '<?= route('comment.searchCommentPost') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                notiSuccess('comment successfully', 'center');
                form[0].reset();
                deleteFileComment($('#deleteFileComment'));
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

            $('#btn-comment-post').click(function(e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                const form = $('form#form_comment');
                let formData = new FormData(form[0]);
                createComment(formData, $(this), form);
            });

            $('#file').change(function() {
                showFile(this);
            });

            $('#deleteFileComment').click(function() {
                deleteFileComment($(this))
            });


        })
    </script>
@endsection
