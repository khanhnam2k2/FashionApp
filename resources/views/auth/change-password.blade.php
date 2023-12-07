<!-- resources/views/auth/change-password.blade.php -->
@extends('auth.layouts.app')
@section('title', 'Đổi mật khẩu - Male Fashion')

@section('content')
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="{{ route('home') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                            <img src="{{ asset('img/logo.png') }}" width="180" alt="">
                        </a>
                        <p class="text-center">Đổi mật khẩu của bạn</p>
                        <form id="form_change_password">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" autofocus class="form-control"
                                        id="current_password">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleCurrentPassword">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <div class="input-group">
                                    <input type="password" name="new_password" class="form-control" id="new_password">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" name="confirm_password" class="form-control"
                                        id="confirm_password">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <button id="btnChangePassword" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đổi mật
                                khẩu</button>
                            <div class="d-flex align-items-center justify-content-center">
                                <p class="fs-4 mb-0 fw-bold">Quay lại trang chủ</p>
                                <a class="text-primary fw-bold ms-1" href="{{ route('register') }}">tại đây</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('web-script')
    <script>
        $(document).ready(function() {
            /**
             * toggle show/hide password
             * @param {Element} passwordInput input password
             * @param {Element} icon icon show/hide
             * */
            function togglePasswordVisibility(passwordInput, icon) {
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                if (type === 'password') {
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }

            }

            // Click to change password
            $('#btnChangePassword').click(function(e) {
                e.preventDefault();
                const formChangePassword = $('form#form_change_password');
                // Remove previous error messages and classes
                formChangePassword.find('.text-danger').remove();
                let formData = new FormData(formChangePassword[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('account.changePassword') }}",
                    contentType: false,
                    processData: false,
                    data: formData,
                }).done(function(res) {
                    const data = res.data.original;
                    if (data.success) {
                        notiSuccess(data.success, 'center', function() {
                            window.location.href = "{{ route('home') }}"
                        })
                    } else {
                        notiError(data.error);
                    }
                }).fail(function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        // Loop through the errors and display them
                        $.each(errors, function(key, value) {
                            const inputField = formChangePassword.find('[name="' + key +
                                '"]');
                            const inputGroup = inputField.parent();
                            inputGroup.after('<div class="text-danger">' + value[0] +
                                '</div>');
                        });
                    } else {
                        notiError();
                    }
                })
            });

            // Click to toggle show/hide current password
            $('#toggleCurrentPassword').click(function() {
                const passwordInput = $('#current_password');
                const icon = $(this).find('i');
                togglePasswordVisibility(passwordInput, icon);
            });

            // Click to toggle show/hide new password
            $('#toggleNewPassword').click(function() {
                const passwordInput = $('#new_password');
                const icon = $(this).find('i');
                togglePasswordVisibility(passwordInput, icon);
            });

            // Click to toggle show/hide confirm password
            $('#toggleConfirmPassword').click(function() {
                const passwordInput = $('#confirm_password');
                const icon = $(this).find('i');
                togglePasswordVisibility(passwordInput, icon);
            });
        })
    </script>
@endsection
