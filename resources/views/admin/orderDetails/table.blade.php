<table class="table text-nowrap mb-0 align-middle">
    <thead>
        <tr>
            <th>Order Id</th>
            <th>Product</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    @php
                        $imagesArray = json_decode($item->productImages, true);
                    @endphp
                    <div class="w-100 d-flex flex-column">
                        <span>{{ $item->productName }}</span>
                        <img src="{{ Storage::url($imagesArray[0]) }}"
                            style="width: 200px;height:200px;object-fit:cover;border-radius:10px" alt="">
                    </div>
                </td>
                <td>{{ $item->size }} </td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->price }}</td>
                <td>${{ $item->price * $item->quantity }}</td>
            </tr>
        @endforeach
        @if (count($data) == 0)
            <td class="align-center" colspan="9" style="background-color: white; font-size : 20px;text-align:center">
                There is no data to display
            </td>
        @endif
    </tbody>
</table>
<div class="row">
    <div class="table-footer" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('admin.order.paging') }}
    </div>
</div>
<style>
    td i {
        font-size: 20px;
    }

    .status-order {
        padding: 5px 2px;
        border-radius: 10px;
    }
</style>
