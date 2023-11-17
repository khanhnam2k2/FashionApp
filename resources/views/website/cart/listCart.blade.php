@if (count($cartItemLimit) > 0)
    <div class="w-100">
        <h6 class="mb-3">{{ count($cartItemLimit) }} Sản phẩm mới thêm</h6>
        @foreach ($cartItemLimit as $item)
            <div class="d-flex justify-content-between align-items-center mb-3 w-100">
                <div class="">
                    @php
                        $imagesArray = json_decode($item->productImage, true);
                    @endphp
                    <img src="{{ Storage::url($imagesArray[0]) }}" width="50" height="50" alt="">
                </div>
                <div class="d-flex flex-column">
                    <p class="text-overflow m-0">{{ $item->productName }} @if ($item->quantity > $item->quantityAvailable)
                            <span class="text-danger">: Hết hàng</span>
                        @else
                            <span class="text-danger"> x {{ $item->quantity }}</span>
                            <p>size: <span class="text-danger">{{ $item->size }}</span></p>
                        @endif
                    </p>
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
    <div class="text-center p-5">
        <div class="">
            <p>Không có sản phẩm nào trong giỏ hàng <i class="fa-regular fa-face-frown"></i></p>
            <a href="{{ route('shop.index') }}" class="btn btn-dark">Mua sắm ngay</a>
        </div>
    </div>
@endif
