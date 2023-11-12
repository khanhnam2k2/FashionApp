@php
    use App\Enums\Status;
@endphp
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tiêu đề bài viết</th>
            <th>Ảnh đại diện</th>
            <th>Tác giả</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->title }}</td>
                <td>
                    <a href="{{ Storage::url($item->image) }}" data-lightbox="image">
                        <img src="{{ Storage::url($item->image) }}"
                            style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px;" alt="">
                    </a>
                </td>
                <td>{{ $item->userCreated }}</td>
                <td>{{ $item->created_at->format('d F Y') }}</td>
                <td><button style="cursor: unset"
                        class="btn btn-{{ $item->status == Status::ON ? 'primary' : 'danger' }}">{{ $item->status == Status::ON ? 'ON' : 'OFF' }}</button>
                </td>
                <td>
                    <div class="d-flex flex-column gap-2">
                        <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                            data-bs-target="#updatePostModal" data-bs-backdrop="static" data-bs-keyboard="false"
                            class="btn btn-success m-1 me-4"><i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh
                            sửa</button>
                        <button id="btnDeletePost" data-id="{{ $item->id }}" class="btn btn-danger"><i
                                class="fa-solid fa-trash-can me-2"></i>Xóa</button>
                    </div>
                </td>
            </tr>
        @endforeach

        @if (count($data) == 0)
            <td class="align-center text-danger" colspan="9"
                style="background-color: white; font-size : 20px;text-align:center">
                Không có bài viết nào để hiển thị!
            </td>
        @endif
    </tbody>
</table>
<div class="row">
    <div class="table-footer" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('admin.post.paging') }}
    </div>
</div>
