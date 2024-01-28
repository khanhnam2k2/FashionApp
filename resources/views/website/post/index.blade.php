@extends('layouts.app')
@php
    use App\Enums\Status;
@endphp
@section('title', 'Bài viết - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-blog set-bg" data-setbg="{{ asset('img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Bài viết</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">
            <div class="form-search d-flex algin-items-center mb-3 justify-content-end">
                <input type="text" class="px-3 py-2 rounded-pill" id="txtSearchPostWeb" placeholder="nhập tên bài viết..."
                    name="namePost">
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
        const iconLoadingPost = "{{ asset('admin/assets/images/loading.svg') }}";
        /**
         * Load cagtegory list
         * @param page current page
         * @param searchName name of the search
         */
        function searchPostWeb(page = 1, searchName = '') {
            $('#post_list').html(`<div class="d-flex justify-content-center mt-5">
                                <img src="${iconLoadingPost}" alt="">
                            </div>`);
            $.ajax({
                url: '<?= route('post.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
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

            // Event enter keyword search
            $('#txtSearchPostWeb').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchPostWeb(1, search);
                } else {
                    searchPostWeb();
                }
            }, 500));

        })
    </script>

@endsection
