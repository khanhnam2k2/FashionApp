@if (count($cartItems) > 0)
    <div class="w-100">
        <h6 class="mb-3">Sản phẩm mới thêm</h6>
        @foreach ($cartItems as $item)
            <div class="d-flex justify-content-between align-items-center mb-3 w-100">
                <div class="">
                    @php
                        $imagesArray = json_decode($item->productImage, true);
                    @endphp
                    <img src="{{ Storage::url($imagesArray[0]) }}" width="50" height="50" alt="">
                </div>
                <div class="d-flex flex-column">
                    <p class="text-overflow m-0">{{ $item->productName }} @if ($item->quantity > $item->quantityAvailable)
                            <span class="btn btn-danger">Hết hàng</span>
                        @else
                            <span class="text-danger"> x {{ $item->quantity }}</span>
                        @endif
                    </p>
                    <p>size: <span class="text-danger">{{ $item->size }}</span></p>
                </div>
                <p class="text-danger">{{ number_format($item->total, 0, ',', '.') }}đ</p>
            </div>
        @endforeach
        <div class="d-flex justify-content-between align-items-center">
            <p>{{ $countCart }} Thêm vào giỏ hàng</p>
            <a href="{{ route('cart.index') }}" class="btn btn-danger">Xem giỏ hàng</a>
        </div>
    </div>
@else
    <div class="text-center">Không có sản phẩm nào trong giỏ hàng</div>
@endif
