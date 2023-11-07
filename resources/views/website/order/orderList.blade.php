@php
    use App\Enums\StatusOrder;
@endphp
@foreach ($data as $item)
    <div class="p-3 mb-3" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
        <div class="border-bottom mb-3">
            @switch($item->status)
                @case(StatusOrder::cancelOrder)
                    <div class="d-flex align-items-center justify-content-between mb-2 ">
                        <div class="d-flex flex-column">
                            <p><span class="text-danger">Code order:</span> {{ $item->code }}</p>
                            <p><span class="text-danger">Order date:</span> {{ $item->created_at }}</p>
                            <p><span class="text-danger">Total amount:</span>: ${{ $item->total_order }}</p>
                            <p><span class="text-danger">Canceled order on:</span>: {{ $item->updated_at }}</p>
                        </div>
                        <div class="">
                            <i class="fa-solid fa-ban text-danger mr-2"></i>
                            <span class="text-start">Cancel Order</span>
                        </div>
                    </div>
                @break

                @case(StatusOrder::orderPlaced)
                    <div class="d-flex align-items-center justify-content-between mb-2 ">
                        <div class="d-flex flex-column">
                            <p><span class="text-danger">Code order:</span> {{ $item->code }}</p>
                            <p><span class="text-danger">Order date:</span> {{ $item->created_at }}</p>
                            <p><span class="text-danger">Total amount:</span>: ${{ $item->total_order }}</p>
                        </div>
                        <div class="">
                            <i class="fa-solid fa-receipt text-dark mr-2"></i>
                            <span class="text-start">Wait for confirmation</span>
                        </div>
                    </div>
                @break

                @case(StatusOrder::confirmInformation)
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex flex-column">
                            <p><span class="text-danger">Code order:</span> {{ $item->code }}</p>
                            <p><span class="text-danger">Order date:</span> {{ $item->created_at }}</p>
                            <p><span class="text-danger">Total amount:</span>: ${{ $item->total_order }}</p>
                        </div>
                        <div class="">
                            <i class="fa-solid fa-circle-dollar-to-slot text-primary mr-2"></i>
                            <span class="text-start">Confirmed successfully</span>
                        </div>
                    </div>
                @break

                @case(StatusOrder::delivering)
                    <div class="d-flex align-items-center justify-content-between mb-2 ">
                        <div class="d-flex flex-column">
                            <p><span class="text-danger">Code order:</span> {{ $item->code }}</p>
                            <p><span class="text-danger">Order date:</span> {{ $item->created_at }}</p>
                            <p><span class="text-danger">Total amount:</span>: ${{ $item->total_order }}</p>
                        </div>
                        <div class="">
                            <i class="fa-solid fa-truck text-warning mr-2"></i>
                            <span class="text-start">Delivering</span>
                        </div>
                    </div>
                @break

                @case(StatusOrder::successfulDelivery)
                    <div class="d-flex align-items-center justify-content-between mb-2 ">
                        <div class="d-flex flex-column">
                            <p><span class="text-danger">Code order:</span> {{ $item->code }}</p>
                            <p><span class="text-danger">Order date:</span> {{ $item->created_at }}</p>
                            <p><span class="text-danger">Total amount:</span>: ${{ $item->total_order }}</p>
                        </div>
                        <div class="">
                            <i class="fa-solid fa-circle-check text-success mr-2"></i>
                            <span class="text-start">Successful delivery</span>
                        </div>
                    </div>
                @break
            @endswitch
        </div>
        <div class="" id="orderDetails_{{ $item->id }}">

        </div>
        <div class="d-flex align-items-center justify-content-end">
            <button class="btn btn-info mr-3 btn-order-details" data-order-id="{{ $item->id }}">See
                details</button>
            @if ($item->status == StatusOrder::cancelOrder)
                <button data-order-id="{{ $item->id }}" class="btn btn-danger btn-repurchase">Repurchase</button>
            @elseif($item->status == StatusOrder::orderPlaced)
                <button data-order-id="{{ $item->id }}" class="btn btn-danger btn-cancel-order">Cancel
                    order</button>
            @endif
        </div>
    </div>
@endforeach
@if (count($data) == 0)
    <div class="mt-2" style="background-color: white; font-size : 24px;text-align:center">
        There are no orders
    </div>
@endif
<div class="row">
    <div class="product-pagination d-flex justify-content-center" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('website.order.paging') }}
    </div>
</div>
