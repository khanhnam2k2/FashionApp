@php
    use App\Enums\Status;
@endphp
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Size - Quantity</th>
            <th>Status</th>
            <th>Option</th>
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
                                        style="width: 100%; height: 100px; object-fit: cover; border-radius: 10px;"
                                        alt="">
                                </a>
                            </div>
                        @endforeach
                    </div>

                </td>
                <td>{{ $item->price }} $</td>
                <td>
                    @php
                        $sizes = explode(',', $item->sizes);
                        $quantities = explode(',', $item->quantities);
                    @endphp
                    @foreach ($sizes as $key => $size)
                        <div class="btn btn-primary mb-2">{{ $size }} - {{ $quantities[$key] }} </div>
                    @endforeach
                </td>
                <td><button style="cursor: unset"
                        class="btn btn-{{ $item->status == Status::ON ? 'primary' : 'danger' }}">{{ $item->status == Status::ON ? 'ON' : 'OFF' }}</button>
                </td>
                <td>
                    <button data-bs-toggle="modal" data-item="{{ json_encode($item) }}"
                        data-bs-target="#updateProductModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        class="btn btn-success m-1 me-4"><i class="fa-solid fa-pen-to-square me-2"></i>Edit</button>
                    <button id="btnDeleteProduct" data-id="{{ $item->id }}" class="btn btn-danger"><i
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
        {{ $data->links('admin.product.paging') }}
    </div>
</div>
