@php
    use App\Enums\UserRole;
@endphp
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCategoryModal">Tạo mới tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_create_account">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="accountName" class="form-label">Họ tên<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="accountName" name="name">
                        </div>
                        <div class="col-md-4">
                            <label for="accountPhone" class="form-label">Số điện thoại</label>
                            <input type="number" class="form-control" id="accountPhone" name="phone">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="accountEmail" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="accountEmail" name="email">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="accountPassword" class="form-label">Mật khẩu<span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="accountPassword" name="password">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu<span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="accountAddress" class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" id="accountAddress"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="role" class="form-label">Vai trò<span class="text-danger">*</span></label>
                        <select name="role" class="form-select" id="role">
                            <option value="{{ UserRole::ADMIN }}">Admin</option>
                            <option value="{{ UserRole::USER }}">Người dùng</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnSubmitAccount" type="button" class="btn btn-primary">Tạo mới
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    #createAccountModal {
        --bs-modal-width: 800px !important;
    }
</style>
<script>
    /**
     * Submit form 
     */
    function doSubmitAccount(btn) {
        let formData = new FormData($('form#form_create_account')[0]);
        showConfirmDialog('Bạn có chắc chắn muốn tạo mới tài khoản này không?', function() {
            btn.text('Đang tạo...');
            btn.prop('disabled', true);
            createAccount(formData, btn);
        });
    }

    /**
     * Create account
     */
    function createAccount(data, btn) {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.account.create') }}",
            contentType: false,
            processData: false,
            data: data,
        }).done(function(res) {
            let response = res.data.original;
            if (response.success) {
                notiSuccess(response.success);
                searchCustomer();
                $('#createAccountModal').modal('toggle');
            } else {
                notiError(response.error);
            }
        }).fail(function(xhr) {
            if (xhr.responseJSON.errors) {
                const errorMessages = xhr.responseJSON.errors;
                for (let fieldName in errorMessages) {
                    notiError(errorMessages[fieldName][0]);
                }
            } else {
                notiError('ok');
            }
        }).always(function() {
            btn.text('Lưu');
            btn.prop('disabled', false);
        })
    }

    $(document).ready(function() {

        // Click to submit the category
        $('#btnSubmitAccount').click(function(e) {
            e.preventDefault();
            doSubmitAccount($(this));
        });

        // Press enter to submit the category
        $('#createAccountModal').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const btnSubmitAccount = $('#btnSubmitAccount');
                doSubmitAccount(btnSubmitAccount);
            }
        });

        // Reset form create account
        $('#createAccountModal').on('shown.bs.modal', function(e) {
            const formAccount = $('form#form_create_account');
            formAccount[0].reset();
        });
    })
</script>
