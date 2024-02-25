@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp
@section('title', 'Shop - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <x-breadcrumb route1="home" nameRoute1="Trang chủ" current="Shop" />
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
                                                    <li class="mb-3 "><span class="categoryProduct active"
                                                            data-id="">Tất
                                                            cả</span>
                                                    </li>
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
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseFour">Size</a>
                                    </div>
                                    <div id="collapseFour" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__size">
                                                <label for="all">Tất cả
                                                    <input type="radio" value="" id="all">
                                                </label>
                                                <label for="S">s
                                                    <input type="radio" value="S" id="S">
                                                </label>
                                                <label for="M">m
                                                    <input type="radio" value="M" id="M">
                                                </label>
                                                <label for="L">l
                                                    <input type="radio" value="L" id="L">
                                                </label>
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
                                    <select id="sortByPrice"
                                        style="padding: 3px 5px;
                                    border-radius: 10px">
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

        /**
         * Load product list
         * @param page current page number
         * @param searchName name of the search
         * @param sortByPrice type sort price
         * @param categoryId id of category
         * @param status status of product
         * @param size size of product
         */
        function searchProductShop(page = 1, searchName = '', sortByPrice = null, categoryId = null, status = null, size =
            null) {
            $.ajax({
                url: '<?= route('shop.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
                    sortByPrice: sortByPrice,
                    categoryId: categoryId,
                    paginate: 9,
                    status: status ?? statusON,
                    size: size
                }
            }).done(function(data) {
                $('#listProduct').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {

            searchProductShop();

            // List products sort by price
            $('#sortByPrice').change(function() {
                let sortByPrice = $(this).val();
                let categoryId = $('#categoryContainer').find('.active').data('id');
                let size = $('.shop__sidebar__size .active').find('input[type="radio"]').val();
                let searchName = $('#txtSearchProduct').val();
                searchProductShop(1, searchName ?? '', sortByPrice, categoryId ?? null, statusON, size);
            });

            // Event enter keyword search
            $('#txtSearchProduct').keyup(debounce(function(e) {
                let searchName = e.currentTarget.value ?? '';
                let categoryId = $('#categoryContainer').find('.active').data('id');
                let size = $('.shop__sidebar__size .active').find('input[type="radio"]').val();
                if (searchName != '') {
                    searchProductShop(1, searchName, $('#sortByPrice').val() ?? null, categoryId ??
                        null,
                        statusON,
                        size);
                } else {
                    searchProductShop(1, '', $('#sortByPrice').val() ?? null, categoryId ?? null,
                        statusON,
                        size);
                }
            }, 500));

            // List product show by category
            $('.categoryProduct').on('click', function() {
                let categoryId = $(this).data('id');
                let sortByPrice = $('#sortByPrice').val();
                let size = $('.shop__sidebar__size .active').find('input[type="radio"]').val();
                let searchName = $('#txtSearchProduct').val();
                $('.categoryProduct').removeClass('active');
                $(this).addClass('active');
                searchProductShop(1, searchName ?? '', sortByPrice ?? null, categoryId, statusON, size);
            });

            // List product show by size
            $('.shop__sidebar__size input[type="radio"]').on('click', function() {
                let selectedSize = $(this).val();
                let sortByPrice = $('#sortByPrice').val();
                let categoryId = $('#categoryContainer').find('.active').data('id');
                let searchName = $('#txtSearchProduct').val();
                searchProductShop(1, searchName ?? '', sortByPrice ?? null,
                    categoryId ?? null, statusON, selectedSize);
            });

            // Add to cart
            $(document).on('click', '.add-cart', function() {
                $(this).prop('disabled', true);
                const id = $(this).data('id');
                const productId = $(this).data('product-id');
                const sizeValue = $(`#size-group-${id}`).find(".active input[type='radio']").val();
                let searchName = $('#txtSearchProduct').val();
                let sortByPrice = $('#sortByPrice').val();
                let categoryId = $('#categoryContainer').find('.active').data('id');
                let size = $('.shop__sidebar__size .active').find('input[type="radio"]').val();

                addToCart(productId, 1, sizeValue, $(this));
                searchProductShop(1, searchName ?? '', sortByPrice ?? null, categoryId ?? null, statusON,
                    size);
            });

        })
    </script>
@endsection
