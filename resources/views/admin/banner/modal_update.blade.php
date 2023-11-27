<div class="modal fade" id="updateBannerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleBannerModal">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_banner">
                    <input type="hidden" name="bannerId" id="bannerId">
                    <div class="mb-4">
                        <label for="titleBanner" class="form-label">Tiêu đề<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="titleBanner" name="title">
                    </div>
                    <div>
                        <label for="imageBanner" class="form-label">Ảnh</label>
                        <input type="file" class="form-control" id="imageBanner" name="image">
                    </div>
                    <div class="w-100 d-flex justify-content-center my-2" id="imageBannerPreviewContainer">
                    </div>
                    <div class="mb-4">
                        <label for="descriptionBanner" class="form-label">Mô tả<span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="descriptionBanner" name="description"></textarea>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Trạng thái<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusBanner">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnSubmitBanner" type="button" class="btn btn-primary">Lưu
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    #updateBannerModal {
        --bs-modal-width: 800px !important;
    }

    #imageBannerPreview {
        width: 400px;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
<script>
    /**
     * Submit form banner
     * @param btn button submit
     */
    function doSubmitBanner(btn) {
        const formBanner = $('form#form_banner');
        let formData = new FormData(formBanner[0]);
        formData.append('statusBanner', $('#cbStatusBanner').is(':checked') ? 1 : 0);
        if ($('#bannerId').val() == '') {
            showConfirmDialog('Bạn có chắc chắn muốn tạo ảnh bìa này không?', function() {
                btn.text('Đang tạo...');
                btn.prop('disabled', true);
                createBanner(formData, btn, formBanner);
            });
        } else {
            showConfirmDialog('Bạn có chắc chắn muốn cập nhật ảnh bìa này không?', function() {
                btn.text('Đang cập nhật...');
                btn.prop('disabled', true);
                updateBanner(formData, btn, formBanner);
            });
        }
    }

    /**
     * Create banner
     * @param data data to create banner
     * @param btn button create banner
     * @param form form create banner
     */
    function createBanner(data, btn, form) {
        // Remove previous error messages and classes
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.banner.create') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Ảnh bìa được tạo mới thành công');
                searchBanner();
                $('#updateBannerModal').modal('toggle');

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
    function updateBanner(data, btn, form) {
        // Remove previous error messages and classes
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.banner.update') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Ảnh bìa được cập nhật thành công');
                searchBanner();
                $('#updateBannerModal').modal('toggle');

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

        // Add/change image for post
        $('#imageBanner').on('change', function() {
            handleImageUpload(this, $('#imageBannerPreview'));
        });

        // Click to submit the banner
        $('#btnSubmitBanner').click(function(e) {
            e.preventDefault();
            doSubmitBanner($(this));
        });

        // Press enter to submit the banner
        $('#updateBannerModal').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const btnSubmitBanner = $('#btnSubmitBanner');
                doSubmitBanner(btnSubmitBanner);
            }
        });

        // Event show category modal
        $('#updateBannerModal').on('shown.bs.modal', function(e) {
            $('#titleBanner').focus();
            let imagePreviewHtml = '';
            const data = $(e.relatedTarget).data('item');
            if (data) {
                imagePreviewHtml = `<img src="/storage/${data.image}" id="imageBannerPreview" />`;
                $('#imageBannerPreviewContainer').html(imagePreviewHtml);
                $("#bannerId").val(data.id);
                $("#titleBanner").val(data.title);
                $("#imageBanner").val("");
                $("#descriptionBanner").val(data.description);
                $('#cbStatusBanner').prop('checked', data.status == 1);
                $('#titleBannerModal').html('Cập nhật ảnh bìa');
            } else {
                $('form#form_banner')[0].reset(); // reset form
                imagePreviewHtml =
                    `<img src="{{ asset('img/default-img.png') }}" id="imageBannerPreview" />`;
                $('#imageBannerPreviewContainer').html(imagePreviewHtml);
                $('#titleBannerModal').html('Thêm mới ảnh bìa');
            }
        });
    })
</script>
