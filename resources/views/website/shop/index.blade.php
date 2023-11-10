@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp
@section('title', 'Shop - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <div class="form-search">
                                <input type="text" name="search" id="txtSearchProduct"
                                    placeholder="nhập tên sản phẩm...">
                                <button onclick="searchProductShop()"><span class="icon_search"></span></button>
                            </div>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Danh mục</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll" id="categoryContainer">
                                                    @foreach ($categories as $category)
                                                        <li class="mb-3 "><span class="categoryProduct"
                                                                data-id="{{ $category->id }}">{{ $category->name }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <a class="btn" style="background: #f3f2ee" href="{{ route('cart.index') }}"><i
                                            class="fa-solid fa-cart-shopping mr-2"></i>Giỏ hàng
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sắp xếp theo giá:</p>
                                    <select id="sortByPrice">
                                        <option value="" disabled selected>Chọn sắp xếp kiểu</option>
                                        <option value="asc">Thấp đến cao</option>
                                        <option value="desc">Cao đến thấp</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="listProduct"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->
@endsection

@section('web-script')
    <script>
        var statusON = {{ Status::ON }};

        function searchProductShop(page = 1, searchName = null, sortByPrice = null, categoryId = null, status) {
            $.ajax({
                url: '<?= route('shop.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchProduct').val(),
                    sortByPrice: sortByPrice,
                    categoryId: categoryId,
                    status: status ?? statusON
                }
            }).done(function(data) {
                $('#listProduct').html(data);
            }).fail(function() {
                notiError();
            });
        }
        $(document).ready(function() {
            searchProductShop();

            // list products sort by price
            $('#sortByPrice').change(function() {
                let sortByPrice = $(this).val();
                let categoryId = $('#categoryContainer').find('.active').data('id');
                searchProductShop(page = 1, searchName = null, sortByPrice = sortByPrice, categoryId =
                    categoryId ?? null);
            });

            // list product show by category
            $('.categoryProduct').on('click', function() {
                let categoryId = $(this).data('id');
                let sortByPrice = $('#sortByPrice').val();
                $('.categoryProduct').removeClass('active');
                $(this).addClass('active');
                searchProductShop(page = 1, searchName = null, sortByPrice = sortByPrice ?? null,
                    categoryId = categoryId);
            });

            $(document).on('click', '.add-cart', function() {
                $(this).prop('disabled', true);
                const id = $(this).data('id');
                const productId = $(this).data('product-id');
                const sizeValue = $(`#size-group-${id}`).find(".active input[type='radio']").val();
                addToCart(productId, 1, sizeValue, $(this));
                searchProductShop();
            })
        })
    </script>
@endsection
