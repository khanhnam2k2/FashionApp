<div class="row">
    @foreach ($data as $item)
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="blog__item">
                <div class="blog__item__pic"
                    style="background-image: url({{ Storage::url($item->image) }});background-repeat: no-repeat;
                    background-size: cover;
                    background-position: top center;">
                </div>
                <div class="blog__item__text">
                    <span><img src="img/icon/calendar.png" alt=""> 16 February 2020</span>
                    <h5>{{ $item->title }}</h5>
                    <a href="#">Read More</a>
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
        {{ $data->links('website.post.paging') }}
    </div>
</div>
