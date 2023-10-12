@extends('layouts.app')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
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
                                <input type="text" name="search" id="txtSearchProduct" placeholder="Search...">
                                <button onclick="searchProductShop()"><span class="icon_search"></span></button>
                            </div>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
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
                                    <button class="btn" style="background: #f3f2ee" onclick="location.reload();">Refresh
                                        Shop </button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sort by Price:</p>
                                    <select id="sortByPrice">
                                        <option value="" disabled selected>Choose type sort</option>
                                        <option value="asc">Low To High</option>
                                        <option value="desc">High To Low</option>
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
<style>
    .product-pagination .pagination {
        border: none;
    }

    .product-pagination .pagination .page-item .page-link {
        border: none;
        background-color: #fff;
        color: #000;
    }

    .product-pagination .pagination .active>.page-link,
    .page-link.active {
        border: 1px solid #000;
        border-radius: 50%;
    }

    .product-pagination .pagination .disabled>.page-link,
    .page-link.disabled {
        opacity: 0.7;
    }
</style>

@section('web-script')
    <script>
        function searchProductShop(page = 1, searchName = null, sortByPrice = null, categoryId = null) {
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
                const productId = $(this).data('id');
                addToCart(productId, 1);
                searchProductShop();
            })
        })
    </script>
@endsection
