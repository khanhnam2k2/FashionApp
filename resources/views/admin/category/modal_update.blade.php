<div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCategoryModal">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_category">
                    <input type="hidden" name="categoryId" id="categoryId">
                    <div class="mb-4">
                        <label for="categoryName" class="form-label">Tên danh mục<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name">
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Trạng thái<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusCate">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnSubmitCategory" type="button" class="btn btn-primary">Lưu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Submit form cateogry
     * @param btn button submit
     */
    function doSubmitCategory(btn) {
        const formCategory = $('form#form_category');
        let formData = new FormData($('form#form_category')[0]);
        formData.append('statusCategory', $('#cbStatusCate').is(':checked') ? 1 : 0);
        if ($('#categoryId').val() == '') {
            showConfirmDialog('Bạn có chắc chắn muốn tạo danh mục này không?', function() {
                btn.text('Đang tạo...');
                btn.prop('disabled', true);
                createCategory(formData, btn, formCategory);
            });
        } else {
            showConfirmDialog('Bạn có chắc chắn muốn cập nhật danh mục này không?', function() {
                btn.text('Đang cập nhật...');
                btn.prop('disabled', true);
                updateCategory(formData, btn, formCategory);
            });
        }
    }

    /**
     * Create category
     * @param data data to create category
     * @param btn button create category
     * @param form form create category
     */
    function createCategory(data, btn, form) {
        // Remove previous error messages and classes
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.category.create') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Danh mục được tạo thành công');
                searchCategory();
                $('#updateCategoryModal').modal('toggle');

            }
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
            btn.text('Lưu');
            btn.prop('disabled', false);
        })

    }

    /**
     * Update category
     * @param data data to update category
     * @param btn button update category
     * @param form form update category
     */
    function updateCategory(data, btn, form) {
        // Remove previous error messages and classes
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.category.update') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Danh mục được cập nhật thành công');
                searchCategory();
                $('#updateCategoryModal').modal('toggle');

            }
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
            btn.text('Lưu');
            btn.prop('disabled', false);
        })
    }

    $(document).ready(function() {

        // Click to submit the category
        $('#btnSubmitCategory').click(function(e) {
            e.preventDefault();
            doSubmitCategory($(this));
        });

        // Press enter to submit the category
        $('#updateCategoryModal').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const btnSubmitCategory = $('#btnSubmitCategory');
                doSubmitCategory(btnSubmitCategory);
            }
        });

        // Event show category modal
        $('#updateCategoryModal').on('shown.bs.modal', function(e) {
            $('#categoryName').focus();
            const data = $(e.relatedTarget).data('item');
            if (data) {
                $("#categoryId").val(data.id);
                $("#categoryName").val(data.name);
                $('#cbStatusCate').prop('checked', data.status == 1);
                $('#titleCategoryModal').html('Cập nhật danh mục');
            } else {
                $('form#form_category')[0].reset(); // reset form
                $('#titleCategoryModal').html('Thêm mới danh mục');
            }
        });
    })
</script>
