@foreach ($data as $item)
    <div class="row border-bottom pb-2 mb-3">
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
            <p class="p-3 text-danger">${{ $item->price }}</p>
        </div>
    </div>
    <div style="text-align: end">
        <p>Total price: <span style="color:red;font-size:20px">${{ $item->price * $item->quantity }}</span></p>
    </div>
@endforeach
