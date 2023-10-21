@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp
@section('title', 'Post - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-blog set-bg" data-setbg="{{ asset('img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Our Blog</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">
            <div class="form-search d-flex algin-items-center mb-3 justify-content-end">
                <input type="text" id="txtSearchPostWeb" placeholder="search here..." name="namePost">
                <button class="btn btn-dark" onclick="searchPostWeb()">Search</button>
            </div>
            <div id="post_list">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->
@endsection

@section('web-script')
    <script>
        /**
         * Load cagtegory list
         */
        function searchPostWeb(page = 1) {
            $.ajax({
                url: '<?= route('post.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchPostWeb').val(),
                    paginate: 3,
                    status: {{ Status::ON }}
                },
            }).done(function(data) {
                $('#post_list').html(data);
            }).fail(function() {
                notiError();
            });
        }
        $(document).ready(function() {
            searchPostWeb();
        })
    </script>

@endsection
