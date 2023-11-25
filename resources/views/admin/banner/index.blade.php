@extends('admin.layouts.app')
@section('content')
    <div class="category-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh sách ảnh bìa</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchBanner" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="nameCategory">
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateBannerModal"
                data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>Tạo ảnh bìa
                mới</button>
        </div>
        <div class="mt-3">
            <div id="banner_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('admin.banner.modal_update')
@endsection
@section('web-script')
    <script>
        const urlDeleteBanner = "{{ route('admin.banner.delete', ['id' => ':id']) }}";

        /**
         * Load banner list
         */
        function searchBanner(page = 1, searchName = '') {
            $.ajax({
                url: '<?= route('admin.banner.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
                }
            }).done(function(data) {
                $('#banner_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchBanner();

            // event enter keyword search
            $('#txtSearchBanner').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchBanner(1, search);
                } else {
                    searchBanner();
                }
            }, 500));

            // delete banner
            $(document).on('click', '#btnDeleteBanner', function() {
                let categoryId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa ảnh bìa này không?', function() {
                    $.ajax({
                        url: urlDeleteBanner.replace(':id', categoryId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Xóa ảnh bìa thành công");
                            searchBanner();
                        }
                    }).fail(function(xhr) {
                        if (xhr.status === 400 && xhr.responseJSON.errors) {
                            const errorMessages = xhr.responseJSON.errors;
                            for (let fieldName in errorMessages) {
                                notiError(errorMessages[fieldName][0]);
                            }
                        } else {
                            notiError();
                        }
                    })
                })
            });

            // update status banner
            $(document).on('change', '.cbBannerStatus', function() {
                $.ajax({
                    type: "PUT",
                    url: "{{ route('admin.banner.updateStatus') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        bannerId: this.value,
                        status: this.checked ? 1 : 0,
                    }
                }).done(function(res) {
                    if (res == 'ok') {
                        notiSuccess('Cập nhật trạng thái thành công');
                    } else {
                        notiError('Cập nhật không thành công');
                    }
                }).fail(function() {
                    notiError('Cập nhật không thành công');
                })
            });

        });
    </script>
@endsection
