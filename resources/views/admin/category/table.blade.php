@php
    use App\Enums\Status;
@endphp
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên danh mục</th>
            <th>Trạng thái</th>
            <th>Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td><label class="toggle">
                        <input type="checkbox" value="{{ $item->id }}" class="cbCateStatus"
                            {{ $item->status == Status::ON ? 'checked' : '' }} />
                        <span class="labels" data-on="ON" data-off="OFF"></span>
                    </label>
                </td>
                <td>
                    <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                        data-bs-target="#updateCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        class="btn btn-success m-1 me-4"><i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh
                        sửa</button>
                    <button id="btnDeleteCate" data-id="{{ $item->id }}" class="btn btn-danger"><i
                            class="fa-solid fa-trash-can me-2"></i>Xóa</button>
                </td>
            </tr>
        @endforeach
        @if (count($data) == 0)
            <td class="align-center text-danger" colspan="9"
                style="background-color: white; font-size : 20px;text-align:center">
                Không có danh mục nào để hiển thị!
            </td>
        @endif
    </tbody>
</table>
<div class="row">
    <div class="table-footer" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('admin.category.paging') }}
    </div>
</div>
