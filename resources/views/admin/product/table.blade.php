@php
    use App\Enums\Status;
@endphp
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên sản phẩm</th>
            <th>Ảnh</th>
            <th>Giá</th>
            <th>Size - Số lượng</th>
            <th>Trạng thái</th>
            <th>Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>
                    @php
                        $images = json_decode($item->images);
                    @endphp
                    <div class="row">
                        @foreach ($images as $image)
                            <div class="col-md-6 mb-2">
                                <a href="{{ Storage::url($image) }}" data-lightbox="image">
                                    <img src="{{ Storage::url($image) }}"
                                        style="width: 300px; height: 200px; object-fit: contain; border-radius: 10px;"
                                        alt="">
                                </a>
                            </div>
                        @endforeach
                    </div>

                </td>
                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                <td>
                    @php
                        $sizes = explode(',', $item->sizes);
                        $quantities = explode(',', $item->quantities);
                    @endphp
                    @foreach ($sizes as $key => $size)
                        <div class="btn btn-dark mb-2">{{ $size }} - {{ $quantities[$key] }} </div>
                    @endforeach
                </td>
                <td><label class="toggle">
                        <input type="checkbox" value="{{ $item->id }}" class="cbProductStatus"
                            {{ $item->status == Status::ON ? 'checked' : '' }} />
                        <span class="labels" data-on="ON" data-off="OFF"></span>
                    </label>
                </td>
                <td>
                    <div class="d-flex justify-between items-center">
                        <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                            data-bs-target="#updateProductModal" data-bs-backdrop="static" data-bs-keyboard="false"
                            class="btn btn-success m-1 me-3"><i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh
                            sửa</button>
                        <button id="btnDeleteProduct" data-id="{{ $item->id }}" class="btn btn-danger me-3"><i
                                class="fa-solid fa-trash-can me-2"></i>Xóa</button>
                        <button data-bs-toggle="modal" class="btn btn-warning btn-view-revenue"
                            data-bs-target="#revenueModal" data-bs-backdrop="static" data-bs-keyboard="false"
                            data-product-id="{{ $item->id }}">Xem doanh
                            thu</button>
                    </div>
                </td>
            </tr>
        @endforeach

        @if (count($data) == 0)
            <td class="align-center text-danger" colspan="9"
                style="background-color: white; font-size : 20px;text-align:center">
                Không có sản phẩm nào để hiển thị!
            </td>
        @endif
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="revenueModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCategoryModal">Doanh thu của sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-around  align-items-center">
                    <input type="hidden" name="productrRevenueId" id="productrRevenueId">
                    <h6>Xem doanh thu theo:</h6>
                    <form id="form_time_revenue" class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center ">
                            <select class="form-select" id="selectMonth">
                                <option value="" disabled selected>Chọn tháng</option>
                                <option value="">Tất cả</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"> Tháng {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <select class="form-select" id="selectYear">
                                <option value="" selected disabled>Chọn năm</option>
                                <option value="">Tất cả</option>
                                @for ($year = date('Y'); $year >= date('Y') - 2; $year--)
                                    <option value="{{ $year }}">
                                        {{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
                <div id="box_revenue" class="mt-3 ">
                    <p>Đang tải...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="table-footer" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('admin.product.paging') }}
    </div>
</div>

<style>
    #box_revenue {
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        padding: 20px 70px;
    }
</style>
