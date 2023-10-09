<div class="row position-relative">
    @foreach ($data as $item)
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="product__item">
                <div class="product__item__pic"
                    style="background-image: url({{ Storage::url($item->image) }});background-repeat: no-repeat;
                    background-size: cover;
                    background-position: top center;">
                    <ul class="product__hover">
                        <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                        <li><a href="{{ route('shop.details', $item->id) }}"><img src="img/icon/search.png"
                                    alt=""></a>
                        </li>
                    </ul>
                </div>
                <div class="product__item__text">
                    <h6>{{ $item->name }}</h6>
                    <a href="#" class="add-cart">+ Add To Cart</a>
                    <h5>${{ $item->price }}</h5>
                    <div class="product__color__select">
                        <label for="pc-4">
                            <input type="radio" id="pc-4">
                        </label>
                        <label class="active black" for="pc-5">
                            <input type="radio" id="pc-5">
                        </label>
                        <label class="grey" for="pc-6">
                            <input type="radio" id="pc-6">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @if (count($data) == 0)
        <div style="position:absolute;top:50%;left:50%;transform: translateX(-50%);font-size:25px">
            There is no data to display
        </div>
    @endif
</div>
<div class="row">
    <div class="product-pagination d-flex justify-content-center" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('website.shop.paging') }}
    </div>
</div>
