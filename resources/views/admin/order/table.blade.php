@php
    use App\Enums\StatusOrder;
@endphp
<table class="table text-nowrap mb-0 align-middle table-resposive">
    <thead>
        <tr>
            <th>Mã đơn hàng</th>
            <th>Tên người đặt</th>
            <th>Ngày đặt</th>
            <th>Địa chỉ giao hàng</th>
            <th>Thanh toán</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->full_name }}</td>
                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                <td class="text-break">{{ $item->city }} - {{ $item->district }} - {{ $item->ward }} -
                    {{ $item->address_details }} </td>
                <td>
                    @if ($item->payment_method == 'vnpay')
                        <span class="">VnPay</span>
                        @if ($item->payment_status == 'success')
                            <span class="text-primary"> - Đã thanh toán</span>
                        @endif
                    @else
                        <span class="">Khi nhận hàng</span>
                    @endif
                </td>
                <td>{{ number_format($item->total_order, 0, ',', '.') }}đ</td>
                <td>
                    @switch($item->status)
                        @case(StatusOrder::cancelOrder)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-danger">
                                <i class="fa-solid fa-ban text-danger"></i>
                                <span class="text-start">Hủy đơn hàng</span>
                            </div>
                        @break

                        @case(StatusOrder::orderPlaced)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-dark">
                                <i class="fa-solid fa-receipt text-dark"></i>
                                <span class="text-start">Chờ xác nhận</span>
                            </div>
                        @break

                        @case(StatusOrder::confirmInformation)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-primary">
                                <i class="fa-solid fa-circle-dollar-to-slot text-primary"></i>
                                <span class="text-start">Xác nhận thành công</span>
                            </div>
                        @break

                        @case(StatusOrder::delivering)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-warning">
                                <i class="fa-solid fa-truck text-warning"></i>
                                <span class="text-start">Đang giao</span>
                            </div>
                        @break

                        @case(StatusOrder::successfulDelivery)
                            <div class="status-order d-flex flex-column align-items-center gap-2 border border-success">
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span class="text-start">Giao hàng thành công</span>
                            </div>
                        @break
                    @endswitch
                </td>
                <td>
                    <div class="d-flex w-100 flex-column gap-3 justify-content-center">
                        @if ($item->status != StatusOrder::successfulDelivery)
                            <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                                data-bs-target="#updateStatusOrderModal" data-bs-backdrop="static"
                                data-bs-keyboard="false" class="btn btn-success m-1 me-3"><i
                                    class="fa-solid fa-pen-to-square me-2"></i>Cập nhật
                                trạng thái</button>
                        @endif
                        <a href="{{ route('admin.order.details', $item->id) }}" class="btn btn-info me-3"><i
                                class="fa-solid fa-eye me-2"></i>Xem chi tiết</a>
                        <button id="btnDeleteOrder" data-id="{{ $item->id }}" class="btn btn-danger"><i
                                class="fa-solid fa-trash-can me-2"></i>Xóa</button>
                    </div>
                </td>
            </tr>
        @endforeach
        @if (count($data) == 0)
            <td class="align-center text-danger" colspan="9"
                style="background-color: white; font-size : 20px;text-align:center">
                Không có đơn đặt hàng nào để hiển thị!
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
