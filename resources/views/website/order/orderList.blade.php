@php
    use App\Enums\StatusOrder;
@endphp
@foreach ($data as $item)
    <div class="p-3 mb-3" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
        <div class="border-bottom mb-3">
            @switch($item->status)
                @case(StatusOrder::cancelOrder)
                    <div class="d-flex align-items-center justify-content-end mb-2 ">
                        <i class="fa-solid fa-ban text-danger mr-2"></i>
                        <span class="text-start">Cancel Order</span>
                    </div>
                @break

                @case(StatusOrder::orderPlaced)
                    <div class="d-flex align-items-center justify-content-end mb-2 ">
                        <i class="fa-solid fa-receipt text-dark mr-2"></i>
                        <span class="text-start">Wait for confirmation</span>
                    </div>
                @break

                @case(StatusOrder::confirmInformation)
                    <div class="d-flex align-items-center justify-content-end mb-2">
                        <i class="fa-solid fa-circle-dollar-to-slot text-primary mr-2"></i>
                        <span class="text-start">Confirmed successfully</span>
                    </div>
                @break

                @case(StatusOrder::delivering)
                    <div class="d-flex align-items-center justify-content-end mb-2 ">
                        <i class="fa-solid fa-truck text-warning mr-2"></i>
                        <span class="text-start">Delivering</span>
                    </div>
                @break

                @case(StatusOrder::successfulDelivery)
                    <div class="d-flex align-items-center justify-content-end mb-2 ">
                        <i class="fa-solid fa-circle-check text-success mr-2"></i>
                        <span class="text-start">Successful delivery</span>
                    </div>
                @break
            @endswitch
        </div>
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
            <div class="col-md-4" style="text-align: end">
                <p class="p-3 text-danger">${{ $item->price }}</p>
            </div>
        </div>
        <div style="text-align: end">
            <p>Total price: <span style="color:red;font-size:20px">${{ $item->price * $item->quantity }}</span></p>
        </div>
    </div>
@endforeach
@if (count($data) == 0)
    <div class="mt-2" style="background-color: white; font-size : 24px;text-align:center">
        There are no orders
    </div>
@endif
