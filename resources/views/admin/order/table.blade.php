@php
    use App\Enums\Status;
    use App\Enums\StatusOrder;
@endphp
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Order date</th>
            <th>Total Order</th>
            <th>Status</th>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->full_name }}</td>
                <td>{{ $item->created_at }}</td>
                <td>${{ $item->total_order }}</td>
                <td>
                    @switch($item->status)
                        @case(StatusOrder::orderPlaced)
                            <span>Wait for confirmation</span>
                        @break

                        @case(StatusOrder::confirmInformation)
                            <span>Confirmed successfully</span>
                        @break

                        @case(StatusOrder::delivering)
                            <span>Delivering</span>
                        @break

                        @case(StatusOrder::successfulDelivery)
                            <span>Successful delivery</span>
                        @break

                        @default
                            <span>Wait for confirmation</span>
                    @endswitch

                </td>
                <td>
                    <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                        data-bs-target="#updateStatusOrderModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        class="btn btn-success m-1 me-4"><i class="fa-solid fa-pen-to-square me-2"></i>Update
                        Status</button>
                    <button id="btnDeleteCate" data-id="{{ $item->id }}" class="btn btn-danger"><i
                            class="fa-solid fa-trash-can me-2"></i>Delete</button>
                </td>
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
