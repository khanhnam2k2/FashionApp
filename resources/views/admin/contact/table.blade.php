<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên khách hàng</th>
            <th>Email</th>
            <th>Nội dung liên hệ</th>
            <th>Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->message }}</td>
                <td>
                    <button id="btnDeleteContact" data-id="{{ $item->id }}" class="btn btn-danger"><i
                            class="fa-solid fa-trash-can me-2"></i>Xóa</button>
                </td>
            </tr>
        @endforeach
        @if (count($data) == 0)
            <td class="align-center text-danger" colspan="9"
                style="background-color: white; font-size : 20px;text-align:center">
                Không có liên hệ nào!
            </td>
        @endif
    </tbody>
</table>
<div class="row">
    <div class="table-footer" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('admin.contact.paging') }}
    </div>
</div>
