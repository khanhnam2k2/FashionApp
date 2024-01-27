<table class="table text-nowrap mb-0 align-middle">
    <thead class="text-dark fs-4">
        <tr>
            <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">#</h6>
            </th>
            <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Tên sản phẩm</h6>
            </th>
            <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Hình ảnh</h6>
            </th>
            <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Số lượng đã bán</h6>
            </th>
            <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Doanh thu</h6>
            </th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-0">{{ $key + 1 }}</h6>
                </td>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1">{{ $item->product_name }}</h6>
                </td>
                <td>
                    @php
                        $images = json_decode($item->product_images);
                    @endphp
                    <div class="row">
                        @foreach ($images as $image)
                            <div class="col-md-6 mb-2">
                                <a href="{{ Storage::url($image) }}" data-lightbox="image">
                                    <img src="{{ Storage::url($image) }}"
                                        style="width: 100%; height: 100px; object-fit: cover; border-radius: 10px;"
                                        alt="">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </td>
                <td class="border-bottom-0">
                    <p class="mb-0 fw-semibold">{{ $item->total_quantity_sold ?? 0 }}</p>
                </td>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 fs-4">{{ number_format($item->revenue, 0, ',', '.') }}đ</h6>
                </td>
            </tr>
        @endforeach
        @if (count($data) == 0)
            <td class="align-center text-danger" colspan="9"
                style="background-color: white; font-size : 20px;text-align:center">
                Không có dữ liệu nào để hiển thị!
            </td>
        @endif
    </tbody>
</table>
<div class="row">
    <div class="table-footer mt-3 me-3">
        {{ $data->links('admin.product.pagingRevenue') }}
    </div>
</div>
