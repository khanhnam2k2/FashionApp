@php
    use App\Enums\UserRole;
@endphp
@foreach ($data as $item)
    <div class="blog__details__quote">
        @if (Auth::user()->role == UserRole::ADMIN || Auth::user()->id == $item->user_id)
            <span data-toggle="modal" data-item="{{ json_encode($item) }}" data-target="#modalCommentPost"
                data-backdrop="static" data-keyboard="false" style="cursor: pointer"><i
                    class="fa fa-pencil-square"></i></span>
        @endif
        <p>“{{ $item->content }}”</p>

        @php
            $fileInfo = json_decode($item->file);
        @endphp
        @if ($fileInfo && $fileInfo->type === 'video/mp4')
            <video src="{{ Storage::url($fileInfo->path) }}" controls width="320" height="240">
            </video>
        @elseif ($fileInfo && $fileInfo->type === 'image/jpeg')
            <a href="{{ Storage::url($fileInfo->path) }}" data-lightbox="image">
                <img src="{{ Storage::url($fileInfo->path) }}"
                    style="width: 320px; height: 200px; object-fit: cover; border-radius: 10px;"class="mb-2"
                    alt="ImageComment">
            </a>
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <h6>_ {{ $item->author }} _</h6>

            <span>{{ $item->created_at->format('d F Y') }}</span>
        </div>
    </div>
@endforeach
<div class="row">
    <div class="product-pagination d-flex justify-content-center mb-5" style="padding: 10px 15px  0px 0px;width:100%">
        {{ $data->links('website.post.pagingComment') }}
    </div>
</div>
