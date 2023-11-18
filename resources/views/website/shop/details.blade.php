@extends('layouts.app')
@section('title', $product->name . ' - Male Fashion')
@section('content')
    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop.index') }}">Shop</a>
                            <span>{{ $product->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            @php
                                $imagesArray = json_decode($product->images, true);
                            @endphp
                            @foreach ($imagesArray as $key => $image)
                                <li class="nav-item">
                                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" data-toggle="tab"
                                        href="#tabs-{{ $key }}" role="tab">
                                        <div class="product__thumb__pic set-bg" data-setbg="{{ Storage::url($image) }}">
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            @foreach ($imagesArray as $k => $image)
                                <div class="tab-pane {{ $k == 0 ? 'active' : '' }}" id="tabs-{{ $k }}"
                                    role="tabpanel">
                                    <div class="product__details__pic__item">
                                        <img src="{{ Storage::url($image) }}" alt="">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4>{{ $product->name }}</h4>
                            <h3>{{ number_format($product->price, 0, ',', '.') }}đ</h3>
                            <p>{{ $product->description }}</p>
                            <div class="product__details__option">
                                <div class="product__details__option__size">
                                    <span>Size:</span>
                                    @php
                                        $sizes = explode(',', $product->sizes);
                                    @endphp
                                    @foreach ($sizes as $k => $size)
                                        <label for="size_{{ $k }}">{{ $size }}
                                            <input type="radio" id="size_{{ $k }}" name="size"
                                                value="{{ $size }}">
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product__details__cart__option">
                                <div class="quantity">
                                    <input type="number" min="1" class="form-control" id="quantityProduct"
                                        value="1">
                                </div>
                                <button id="addToCart" class="btn primary-btn">Thêm vào giỏ hàng</button>
                                <div class="mt-3">
                                    <h5 class="d-none"><span id="product-available">0</span> sản phẩm có sẵn</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-5" role="tab">Mô tả sản phẩm</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-6" role="tab">Đánh giá
                                        khách hàng</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">{{ $product->description ?? 'Không có mô tả' }}</p>
                                    </div>
                                </div>
                                <div class="tab-pane active" id="tabs-6" role="tabpanel">
                                    <div class="comment-container" id="commentProductList"></div>
                                    <div class="blog__details__comment mb-5">
                                        <button type="button" class="btn btn-dark" data-toggle="modal"
                                            data-target="#modalCommentProduct">
                                            Để lại đánh giá của bạn
                                        </button>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('website.shop.modelComment')
    <!-- Shop Details Section End -->
@endsection
@section('web-script')
    <script>
        const urlGetQuantityOfSize = "{{ route('shop.getQuantityOfSize', ['size' => ':size']) }}";
        const urlDeleteComment = "{{ route('comment.delete', ['id' => ':id']) }}";

        function getQuantityOfSize(size, productId) {
            $.ajax({
                type: "GET",
                url: urlGetQuantityOfSize.replace(':size', size),
                data: {
                    productId: productId
                }
            }).done(function(res) {
                const totalQuantityOfSize = res.data;
                $('#product-available').parent().removeClass('d-none');
                $('#product-available').text(totalQuantityOfSize ?? '0');
            }).fail(function() {
                notiError();
            })
        }

        function showFile(input) {
            if (input.files && input.files[0]) {
                let file = input.files[0];
                let fileCommentHtml = '';
                if (file.type.startsWith("image/")) {
                    fileCommentHtml = `<img src="${URL.createObjectURL(file)}"  alt="Image Comment" />`;
                } else if (file.type.startsWith("video/")) {
                    fileCommentHtml = `<video src="${URL.createObjectURL(file)}" controls></video>`;
                }
                $('#previewFileCommentProduct').html(fileCommentHtml);
                $('#deleteFileCommentProduct').show();
            }
        }

        function deleteFileComment(btn) {
            $('#file').val('');
            $('#previewFileCommentProduct').empty();
            $('input[name="fileOld"]').val('');
            btn.hide();
        }

        function searchCommentProduct(page = 1) {
            $.ajax({
                url: '<?= route('comment.searchCommentProduct') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    typeComment: 'product',
                }
            }).done(function(data) {
                $('#commentProductList').html(data);
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
                    notiError('Please enter review content');
                    return;
                }
                notiSuccess('Đánh giá thành công', 'center');
                form[0].reset();
                deleteFileComment($('#deleteFileCommentProduct'));
                $('#modalCommentProduct').modal('toggle');
                searchCommentProduct();
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
                    notiError('Please enter review content');
                    return;
                } else {
                    const data = res.data.original;
                    if (data.error) {
                        notiError(data.error);
                        return;
                    }
                }
                notiSuccess('Cập nhật đánh giá thành công', 'center');
                form[0].reset();
                deleteFileComment($('#deleteFileCommentProduct'));
                $('#modalCommentProduct').modal('toggle');
                searchCommentProduct();
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
            searchCommentProduct();

            // create review product
            $('#btn-comment-product').click(function(e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                const form = $('form#form_comment_product');
                let formData = new FormData(form[0]);
                const commentId = $('#commentId').val();
                if (commentId == '') {
                    createComment(formData, $(this), form);
                } else {
                    updateComment(formData, $(this), form);
                }
            });

            // chang file review product in shop
            $('#file').change(function() {
                showFile(this);
            });

            // delete file review product
            $('#deleteFileCommentProduct').click(function() {
                deleteFileComment($(this))
            });

            $('#addToCart').on('click', function(e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                const selectedSize = $('input[name="size"]:checked');
                const sizeValue = selectedSize.val();
                const productId = {{ $product->id }};
                const quantity = parseInt($('#quantityProduct').val());
                addToCart(productId, quantity, sizeValue, $(this));
            });

            $('input[name="size"]').on('change', function(e) {
                const size = $(this).val();
                const productId = {{ $product->id }};
                getQuantityOfSize(size, productId);
            });


            $('#modalCommentProduct').on('shown.bs.modal', function(e) {
                const data = $(e.relatedTarget).data('item');
                let filePreviewHtml = '';
                if (data) {
                    const fileData = JSON.parse(data.file);
                    $("input[name='commentId']").val(data.id);
                    $("textarea[name='content']").val(data.content);
                    $("input[name='fileOld']").val(data.file);
                    if (fileData) {
                        $('#deleteFileCommentProduct').show();
                        if (fileData.type.startsWith('image/'))
                            filePreviewHtml = `<img src="/storage/${fileData.path}" />`;
                        else if (fileData.type.startsWith('video/')) {
                            filePreviewHtml =
                                `<video src="/storage/${fileData.path}" controls />`;
                        }
                    }
                    $('#previewFileCommentProduct').html(filePreviewHtml);
                    $('#titleComment').html('Chỉnh sửa đánh giá');
                } else {
                    $("input[name='commentId']").val('');
                    $("textarea[name='content']").val('');
                    $('#previewFileCommentProduct').empty();
                    $("input[name='fileOld']").val('');
                    $('#deleteFileCommentProduct').hide();
                    $('#titleComment').html('Đánh giá');
                }
            });

            // delete comment
            $(document).on('click', '.delete-comment-product', function() {
                let commentId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa đánh giá này?', function() {
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
                            searchCommentProduct();
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
