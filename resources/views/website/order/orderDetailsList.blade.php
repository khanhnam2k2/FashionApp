@foreach ($data as $item)
    <div class="row border-bottom border-top p-2 mb-3">
        <div class="col-md-8">
            <div class="d-flex">
                <div class="">
                    @php
                        $imagesArray = json_decode($item->productImages, true);
                    @endphp
                    <img src="{{ Storage::url($imagesArray[0]) }}" style="width:100px;height:100px;object-fit:cover"
                        alt="">
                </div>
                <div class="ml-2">
                    <h5>{{ $item->productName }}</h5>
                    <p>size: {{ $item->size }}</p>
                    <p>x{{ $item->quantity }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 " style="text-align: end">
            <p class="p-3">Thành tiền
                <span class="text-danger">: {{ number_format($item->total, 0, ',', '.') }}đ</span>
            </p>
            <div class=""><a href="{{ route('shop.details', $item->productId) }}" class="btn btn-warning">Đánh
                    giá</a></div>

        </div>
    </div>
@endforeach
