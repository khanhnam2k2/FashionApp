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
                    <span><img src="{{ asset('img/icon/calendar.png') }}" alt="">
                        {{ $item->created_at->format('d - m - Y') }}
                    </span>
                    <h5>{{ $item->title }}</h5>
                    <a href="{{ route('post.details', $item->id) }}">Đọc thêm</a>
                </div>
            </div>
        </div>
    @endforeach
    @if (count($data) == 0)
        <div class="col-12 text-center my-4">
            <h2>Không có bài viết nào!</h2>
        </div>
    @endif
</div>
<div class="row">
    <div class="product-pagination d-flex justify-content-center" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('website.post.pagingPost') }}
    </div>
</div>
