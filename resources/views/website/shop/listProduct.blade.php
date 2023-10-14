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
                    <button data-id="{{ $item->id }}" class="add-cart btn">+ Add To
                        Cart</button>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>${{ $item->price }}</h5>
                        <div class="size-group btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn ">
                                <input type="radio" name="size" id="s" value="S"> S
                            </label>
                            <label class="btn active ">
                                <input type="radio" checked name="size" id="m" value="M"> M
                            </label>
                            <label class="btn ">
                                <input type="radio" name="size" id="l" value="L"> L
                            </label>
                            <label class="btn ">
                                <input type="radio" name="size" id="xl" value="XL"> XL
                            </label>
                        </div>
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
