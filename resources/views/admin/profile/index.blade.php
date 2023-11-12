@extends('admin.layouts.app')
@section('content')
    <div class="p-3">
        <div class="p-4" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px; min-height:80vh">
            <div class="border-bottom mb-2">
                <h3>Hồ sơ của tôi</h3>
                <p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
            </div>
            <form id="form_update_profile">
                @csrf
                <div class="row m-3">
                    <div class="col-md-8" style="border-right: 1px solid #000">
                        <div class="row mb-3">
                            <div class="col-md-4">Email đăng nhập</div>
                            <div class="col-md-8">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">Tên</div>
                            <div class="col-md-8"><input class="form-control" name="name" type="text"
                                    value="{{ Auth::user()->name ?? '' }}"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">Số điện thoại</div>
                            <div class="col-md-8"><input class="form-control" type="text" name="phone"
                                    placeholder="{{ Auth::user()->phone ? '' : 'Số điện thoại liên hệ' }}"
                                    value="{{ Auth::user()->phone ?? '' }}"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">Địa chỉ</div>
                            <div class="col-md-8">
                                <textarea name="address" class="form-control" placeholder="{{ Auth::user()->address ? '' : 'Địa chỉ của bạn' }}">{{ Auth::user()->address ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="btnUpdateProfile" class="btn btn-danger mt-3">Lưu</button>
                        </div>
                    </div>
                    <div class="col-md-4 px-5 py-3">
                        <div class="">
                            <img id="avatar_img"
                                src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('img/avatar.png') }}"
                                alt="avatart" style="width: 200px;height:200px;border-radius:50%;object-fit:cover">
                        </div>
                        <div class="text-center me-4 mt-2">
                            <label class="btn btn-dark " for="avatar_admin">Chọn ảnh</label>
                            <input type="file" name="avatar" class="d-none" id="avatar_admin">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('web-script')
    <script>
        /*
         * Update profile admin
         *param data data to update profile
         *param btn button to update
         */
        function updateProfile(data, btn) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.profile.updateProfile') }}",
                contentType: false,
                processData: false,

                data: data,
            }).done(function(res) {
                notiSuccess('Cập nhật hồ sơ thành công');
            }).fail(function(xhr) {
                if (xhr.status === 400 && xhr.responseJSON.errors) {
                    const errorMessages = xhr.responseJSON.errors;
                    for (let fieldName in errorMessages) {
                        notiError(errorMessages[fieldName][0]);
                    }
                } else {
                    notiError();
                }
            }).always(function() {
                btn.prop('disabled', false);
                btn.text('Lưu');
            })
        }

        $(document).ready(function() {

            // change image profile
            $('#avatar_admin').change(function() {
                handleImageUpload(this, $('#avatar_img'));
            });

            // click to update profile
            $('#btnUpdateProfile').click(function(e) {
                e.preventDefault();
                const btnUpdateProfile = $(this);
                let formData = new FormData($('form#form_update_profile')[0]);
                showConfirmDialog('Bạn chắc chắn muốn cập nhật hồ sơ của bạn?', function() {
                    btnUpdateProfile.prop('disabled', true);
                    btnUpdateProfile.text('Đang cập nhật...');
                    updateProfile(formData, btnUpdateProfile);
                })
            })
        })
    </script>
@endsection
