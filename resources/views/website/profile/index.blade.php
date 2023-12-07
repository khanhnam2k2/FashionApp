@extends('layouts.app')
@section('title', 'Hồ sơ của bạn - Male Fashion')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Hồ sơ của bạn</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <span>Hồ sơ của bạn</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="spad">
        <div class="container order-container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-4">
                    <div class="profile">
                        <h4>{{ Auth::user()->name }}</h4>
                        <ul>
                            <li>
                                <a class="{{ request()->is('profile') ? 'active' : '' }}"
                                    href="{{ route('profile.index') }}"><i class="fa-regular fa-user mr-2"></i>Trang cá
                                    nhân</a>
                            </li>
                            <li class="mb-4">
                                <a class="pl-4" href="{{ route('account.changePasswordForm') }}">Đổi mật khẩu</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('order') ? 'active' : '' }}" href="{{ route('order.index') }}"><i
                                        class="fa-solid fa-receipt mr-2"></i>Đơn mua</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="profile-container col-lg-10 col-md-10 col-sm-8">
                    <div class="border-bottom mb-2">
                        <h4>Hồ sơ của tôi</h4>
                        <p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                    </div>
                    <form id="form_update_profile">
                        @csrf
                        <div class="card-profile row">
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
                                <div class="text-center mt-2">
                                    <label class="btn btn-dark " for="avatar_user">Chọn ảnh</label>
                                    <input type="file" name="avatar" class="d-none" id="avatar_user">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('web-script')
    <script>
        /*
         * Update profile user
         * @param data data to update profile
         * @param btn button to update
         * @param form form update
         */
        function updateProfile(data, btn, form) {
            // Remove previous error messages and classes
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').remove();
            $.ajax({
                type: "POST",
                url: "{{ route('profile.updateProfile') }}",
                contentType: false,
                processData: false,
                data: data,
            }).done(function(res) {
                notiSuccess('Cập nhật hồ sơ thành công', 'center', function() {
                    window.location.reload();
                }, 1000);
            }).fail(function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (xhr.status === 400 && errors) {
                    // Loop through the errors and display them
                    $.each(errors, function(key, value) {
                        const inputField = form.find('[name="' + key + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="invalid-feedback">' + value[0] +
                            '</div>');
                    });
                } else {
                    notiError();
                }
            }).always(function() {
                btn.prop('disabled', false);
                btn.text('Lưu');
            })
        }
        $(document).ready(function() {

            // Change image profile
            $('#avatar_user').change(function() {
                handleImageUpload(this, $('#avatar_img'));
            });

            // Click to update profile
            $('#btnUpdateProfile').click(function(e) {
                e.preventDefault();
                const btnUpdateProfile = $(this);
                const formUpdateProfile = $('form#form_update_profile');
                let formData = new FormData(formUpdateProfile[0]);
                showConfirmDialog('Bạn chắc chắn muốn cập nhật hồ sơ của bạn?', function() {
                    btnUpdateProfile.prop('disabled', true);
                    btnUpdateProfile.text('Đang cập nhật...');
                    updateProfile(formData, btnUpdateProfile, formUpdateProfile);
                });
            })
        })
    </script>
@endsection
