@php
    use App\Enums\Status;
    use App\Enums\StatusOrder;
@endphp
<table class="table text-nowrap mb-0 align-middle">
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
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-dark">
                                <i class="fa-solid fa-receipt text-dark"></i>
                                <span class="text-start">Wait for confirmation</span>
                            </div>
                        @break

                        @case(StatusOrder::confirmInformation)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-primary">
                                <i class="fa-solid fa-circle-dollar-to-slot text-primary"></i>
                                <span class="text-start">Confirmed successfully</span>
                            </div>
                        @break

                        @case(StatusOrder::delivering)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-warning">
                                <i class="fa-solid fa-truck text-warning"></i>
                                <span class="text-start">Delivering</span>
                            </div>
                        @break

                        @case(StatusOrder::successfulDelivery)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-success">
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span class="text-start">Successful delivery</span>
                            </div>
                        @break
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
<style>
    td i {
        font-size: 20px;
    }

    .status-order {
        /* border: 1px solid blue; */
        padding: 5px 2px;
        border-radius: 10px;
    }
</style>
