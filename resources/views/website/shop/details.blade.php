@extends('layouts.app')
@section('title', 'Shop Details - Male Fashion')
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
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-lg-8 col-md-9 col-xs-12">
                        <div class="image__product">
                            <img src="{{ Storage::url($product->image) }}" style="border-radius: 10px" alt="">
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4>{{ $product->name }}</h4>
                            <h3>${{ $product->price }}</h3>
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
                                <button id="addToCart" class="btn primary-btn">add to cart</button>
                                <div class="mt-3">
                                    <h5 class="d-none"><span id="product-available">0</span> products available</h5>
                                </div>
                            </div>

                            <div class="product__details__btns__option">
                                <a href="#"><i class="fa fa-heart"></i> add to wishlist</a>
                            </div>
                            <div class="product__details__last__option">
                                <h5><span>Guaranteed Safe Checkout</span></h5>
                                <img src="{{ asset('img/shop-details/details-payment.png') }}" alt="">
                                <ul>
                                    @if ($product->sku)
                                        <li><span>SKU:</span> {{ $product->sku }}</li>
                                    @endif
                                    <li><span>Categories:</span> {{ $product->categoryName }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5"
                                        role="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer
                                        Previews(5)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional
                                        information</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">Nam tempus turpis at metus scelerisque placerat nulla deumantos
                                            solicitud felis. Pellentesque diam dolor, elementum etos lobortis des mollis
                                            ut risus. Sedcus faucibus an sullamcorper mattis drostique des commodo
                                            pharetras loremos.</p>
                                        <div class="product__details__tab__content__item">
                                            <h5>Products Infomation</h5>
                                            <p>A Pocket PC is a handheld computer, which features many of the same
                                                capabilities as a modern PC. These handy little devices allow
                                                individuals to retrieve and store e-mail messages, create a contact
                                                file, coordinate appointments, surf the internet, exchange text messages
                                                and more. Every product that is labeled as a Pocket PC must be
                                                accompanied with specific software to operate the unit and must feature
                                                a touchscreen and touchpad.</p>
                                            <p>As is the case with any new technology product, the cost of a Pocket PC
                                                was substantial during it’s early release. For approximately $700.00,
                                                consumers could purchase one of top-of-the-line Pocket PCs in 2003.
                                                These days, customers are finding that prices have become much more
                                                reasonable now that the newness is wearing off. For approximately
                                                $350.00, a new Pocket PC can now be purchased.</p>
                                        </div>
                                        <div class="product__details__tab__content__item">
                                            <h5>Material used</h5>
                                            <p>Polyester is deemed lower quality due to its none natural quality’s. Made
                                                from synthetic materials, not natural like wool. Polyester suits become
                                                creased easily and are known for not being breathable. Polyester suits
                                                tend to have a shine to them compared to wool and cotton suits, this can
                                                make the suit look cheap. The texture of velvet is luxurious and
                                                breathable. Velvet is a great choice for dinner party jacket and can be
                                                worn all year round.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-6" role="tabpanel">
                                    <div class="comment-container" id="commentProductList">

                                    </div>
                                    <div class="blog__details__comment">
                                        <h4>Leave A Review</h4>
                                        <form id="form_comment_product">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="commentType" value="product">
                                                <input type="hidden" name="productId" value="{{ $product->id }}">
                                                <div class="col-lg-12 border">
                                                    <textarea name="content" placeholder="Comment"></textarea>
                                                    <label for="file">📸</label>
                                                    <input type="file" class="form-control d-none" id="file"
                                                        name="file">
                                                </div>
                                                <div class="position-relative mt-2">
                                                    <div id="previewFileCommentProduct">
                                                    </div>
                                                    <span id="deleteFileCommentProduct"
                                                        style="display: none;cursor:pointer"><i
                                                            class="fa fa-close"></i></span>
                                                </div>
                                                <div class="col-lg-12 text-center mt-3">
                                                    <button id="btn-comment-product" class="site-btn">Post Review</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-7" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">Nam tempus turpis at metus scelerisque placerat nulla deumantos
                                            solicitud felis. Pellentesque diam dolor, elementum etos lobortis des mollis
                                            ut risus. Sedcus faucibus an sullamcorper mattis drostique des commodo
                                            pharetras loremos.</p>
                                        <div class="product__details__tab__content__item">
                                            <h5>Products Infomation</h5>
                                            <p>A Pocket PC is a handheld computer, which features many of the same
                                                capabilities as a modern PC. These handy little devices allow
                                                individuals to retrieve and store e-mail messages, create a contact
                                                file, coordinate appointments, surf the internet, exchange text messages
                                                and more. Every product that is labeled as a Pocket PC must be
                                                accompanied with specific software to operate the unit and must feature
                                                a touchscreen and touchpad.</p>
                                            <p>As is the case with any new technology product, the cost of a Pocket PC
                                                was substantial during it’s early release. For approximately $700.00,
                                                consumers could purchase one of top-of-the-line Pocket PCs in 2003.
                                                These days, customers are finding that prices have become much more
                                                reasonable now that the newness is wearing off. For approximately
                                                $350.00, a new Pocket PC can now be purchased.</p>
                                        </div>
                                        <div class="product__details__tab__content__item">
                                            <h5>Material used</h5>
                                            <p>Polyester is deemed lower quality due to its none natural quality’s. Made
                                                from synthetic materials, not natural like wool. Polyester suits become
                                                creased easily and are known for not being breathable. Polyester suits
                                                tend to have a shine to them compared to wool and cotton suits, this can
                                                make the suit look cheap. The texture of velvet is luxurious and
                                                breathable. Velvet is a great choice for dinner party jacket and can be
                                                worn all year round.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->
@endsection
@section('web-script')
    <script>
        const urlGetQuantityOfSize = "{{ route('shop.getQuantityOfSize', ['size' => ':size']) }}";

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
                notiSuccess('Review successfully', 'center');
                form[0].reset();
                deleteFileComment($('#deleteFileCommentProduct'));
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
                createComment(formData, $(this), form);
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
                $(this).prop('disabled', true)
                const selectedSize = $('input[name="size"]:checked');
                const sizeValue = selectedSize.val();
                const productId = {{ $product->id }};
                const quantity = $('#quantityProduct').val();
                addToCart(productId, quantity, sizeValue, $(this));
            });

            $('input[name="size"]').on('change', function(e) {
                const size = $(this).val();
                const productId = {{ $product->id }};
                getQuantityOfSize(size, productId);
            })

        })
    </script>
@endsection
