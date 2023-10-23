@foreach ($data as $item)
    <div class="blog__details__quote mt-4">
        <i class="fa fa-quote-left"></i>
        <p>“{{ $item->content }}”</p>
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
