@php
    use App\Enums\UserRole;
@endphp
<div class="my-3">
    <h3>Reviews ({{ $data->total() }})</h3>
</div>
@foreach ($data as $item)
    <div class="blog__details__quote mt-4">
        @auth
            @if (Auth::user()->id == $item->user_id)
                <span data-toggle="modal" data-item="{{ json_encode($item) }}" data-target="#modalCommentProduct"
                    data-backdrop="static" data-keyboard="false" style="cursor: pointer"><i
                        class="fa fa-pencil-square"></i></span>
            @endif
        @endauth
        <div class="d-flex justify-content-between align-items-center">
            <p>“{{ $item->content }}”</p>
            @auth
                @if (Auth::user()->role == UserRole::ADMIN || Auth::user()->id == $item->user_id)
                    <span data-id="{{ $item->id }}" class="delete-comment-product btn btn-danger mb-4">X</span>
                @endif
            @endauth
        </div>
        @php
            $fileInfo = json_decode($item->file);
        @endphp
        @if ($fileInfo && $fileInfo->type === 'video/mp4')
            <video src="{{ Storage::url($fileInfo->path) }}" controls width="320" height="240">
            </video>
        @elseif ($fileInfo && $fileInfo->type === 'image/jpeg')
            <img src="{{ Storage::url($fileInfo->path) }}"
                style="width: 320px;height:240px;object-fit:cover;border-radius:10px" class="mb-2" alt="Image">
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <h6>_ {{ $item->author }} _</h6>

            <span>{{ $item->created_at->format('d F Y') }}</span>
        </div>
    </div>
@endforeach
<div class="row">
    <div class="product-pagination d-flex justify-content-center mb-5" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('website.shop.pagingComment') }}
    </div>
</div>
