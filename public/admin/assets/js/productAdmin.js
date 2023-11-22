

let arrayImagesUpload = []; // array of images product upload
/**
 * Load product list
 * @param {Number} page current page
 */
function searchProduct(page = 1, searchName = '', categoryId = null) {
    $.ajax({
        url: globalRouter.urlSearchPost + '?page=' + page,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            searchName: searchName,
            categoryId: categoryId,
        },
    }).done(function (data) {
        $('#product_table').html(data);
    }).fail(function () {
        notiError();
    });
}

/**
 * Submit product
 */
function doSubmitProduct(btn) {
    let formData = new FormData($('form#form_product')[0]);
    formData.append('statusProduct', $('#cbStatusProduct').is(':checked') ? 1 : 0);
    if (arrayImagesUpload.length > 0) {
        for (let i = 0; i < arrayImagesUpload.length; i++) {
            formData.append('images[]', arrayImagesUpload[i]);
        }
    }
    if ($('#productId').val() == '') {
        showConfirmDialog('Bạn có chắc chắn muốn tạo sản phẩm này không?', function () {
            btn.text('Đang tạo...');
            btn.prop('disabled', true);
            createProduct(formData, btn);
        });
    } else {
        showConfirmDialog('Bạn có chắc chắn muốn cập nhật sản phẩm này?', function () {
            btn.text('Đang cập nhật...');
            btn.prop('disabled', true);
            updateProduct(formData, btn);
        });
    }
}

/**
 * Create product
 * @param {FormData} data data product send server to create new product
 * @param {Element} btn button to submit
 */
function createProduct(data, btn) {
    btn.prop('disabled', true);
    $.ajax({
        type: 'POST',
        url: globalRouter.urlCreateProduct,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
    }).done(function (res) {
        if (res == 'ok') {
            notiSuccess('Sản phẩm được tạo thành công');
            searchProduct();
            $('#updateProductModal').modal('toggle');
            arrayImagesUpload = [];
        }
    }).fail(function (xhr) {
        if (xhr.status === 400 && xhr.responseJSON.errors) {
            const errorMessages = xhr.responseJSON.errors;
            for (let fieldName in errorMessages) {
                notiError(errorMessages[fieldName][0]);
            }
        } else {
            notiError();
        }
    }).always(function () {
        btn.text('Lưu');
        btn.prop('disabled', false);
    })
}

/**
 * Update product
 * @param {FormData} data data product send server to update product
 * @param {Element} btn button to submit
 */
function updateProduct(data, btn) {
    btn.prop('disabled', true);
    $.ajax({
        type: 'POST',
        url: globalRouter.urlUpdateProduct,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,

    }).done(function (res) {
        if (res == 'ok') {
            notiSuccess('Sản phẩm được cập nhật thành công');
            searchProduct();
            $('#updateProductModal').modal('toggle');
            arrayImagesUpload = [];
        }
    }).fail(function (xhr) {
        if (xhr.status === 400 && xhr.responseJSON.errors) {
            const errorMessages = xhr.responseJSON.errors;
            for (let fieldName in errorMessages) {
                notiError(errorMessages[fieldName][0]);
            }
        } else {
            notiError();
        }
    }).always(function () {
        btn.text('Lưu');
        btn.prop('disabled', false);
    })
}

/**
 * Render html images upload for product
 * @param {Array} array list File image upload
 */
function innerHtmlImageUpload(array) {
    let listFileUpload = $('#imageProductPreviewContainer');
    let htmlContainer = $('<div class="row"></div>');
    array.forEach(function (file, _) {
        let imageUrl = URL.createObjectURL(file);
        let html = `<div class="col-md-3">
                <img src="${imageUrl}" style="width:100%;height:200px;object-fit:cover" />
            </div>`;
        htmlContainer.append(html);
    });
    listFileUpload.html(htmlContainer);
}


$(document).ready(function () {
    searchProduct();

    // search by category
    $('#sortByCategory').on('change', function () {
        let categoryId = $(this).val();
        searchProduct(page = 1, $('#txtSearchProduct').val() ?? '', categoryId = categoryId ?? null);
    });

    // event enter keyword search
    $('#txtSearchProduct').keyup(debounce(function (e) {
        let search = e.currentTarget.value ?? '';
        if (search != '') {
            searchProduct(1, search, $('#sortByCategory').val() ?? null);
        } else {
            searchProduct(1, '', $('#sortByCategory').val() ?? null);

        }
    }, 500));

    // Delete product
    $(document).on('click', '#btnDeleteProduct', function () {
        let productId = $(this).data('id');
        showConfirmDialog('Bạn có chắc chắn muốn xóa sản phẩm này?', function () {
            $.ajax({
                url: globalRouter.urlDeleteProduct.replace(':id', productId),
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function (res) {
                if (res == 'ok') {
                    notiSuccess("Đã xóa sản phẩm thành công");
                    searchProduct();
                }
            }).fail(function (xhr) {
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

    // Update status product
    $(document).on('change', '.cbProductStatus', function () {
        $.ajax({
            type: "PUT",
            url: globalRouter.urlUpdateStatusProduct,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                productId: this.value,
                status: this.checked ? 1 : 0,
            }
        }).done(function (res) {
            if (res == 'ok') {
                notiSuccess('Cập nhật trạng thái thành công');
            } else {
                notiError('Cập nhật không thành công');
            }
        }).fail(function () {
            notiError('Cập nhật không thành công');
        })
    });

    // Add new row size to product
    $('#addSize').click(function (e) {
        e.preventDefault();
        let newField = `
            <div class="row ">
                <span class="removeSize text-end mt-2" style="cursor:pointer;color:red"><i class="fa-solid fa-circle-xmark"></i></span>
                <div class="col-md-6">
                    <label for="productSize" class="form-label">Size<span
                            class="text-danger">*</span></label>
                    <select name="sizes[]" class="form-select" id="productSize">
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="productQuantity" class="form-label">Số lượng<span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="number" value="1" name="quantity[]">
                </div>
            </div>`;
        $('#sizeAndQuantityFields').append(newField);
    });

    // Remove row size
    $('#sizeAndQuantityFields').on('click', '.removeSize', function () {
        $(this).parent().remove();
    });

    // Add/Change images for product
    $('#productImage').on('change', function () {
        let files = this.files;
        for (let i = 0; i < files.length; i++) {
            if (arrayImagesUpload.length > 3) {
                arrayImagesUpload = [];
                notiError('Maximum 4 photos');
                return;
            } else {
                arrayImagesUpload.push(files[i]);
            }
        }
        innerHtmlImageUpload(arrayImagesUpload);
        $('#btn-delete-images').show();
    });

    // Remove images for product
    $('#btn-delete-images').on('click', function () {
        $('#imageProductPreviewContainer').empty();
        arrayImagesUpload = [];
        $(this).hide();
    });

    // Click to submit the product
    $('#btnSubmitProduct').click(function (e) {
        e.preventDefault();
        doSubmitProduct($(this));
    });

    // Press enter to submit the product
    $('#updateProductModal').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const btnSubmitProduct = $('#btnSubmitProduct');
            doSubmitProduct(btnSubmitProduct);
        }
    });

    // Event show product modal
    $('#updateProductModal').on('shown.bs.modal', function (e) {
        $('#productName').focus();
        const data = $(e.relatedTarget).data('item');
        if (data) {
            const arrayDataImages = JSON.parse(data.images);
            const sizes = data.sizes.split(',');
            const quantities = data.quantities.split(',');
            let rowSizeQuantityContainer = $('<div></div>');
            let rowImages = $('<div class="row"></div>');
            $('#productId').val(data.id);
            $('#productName').val(data.name);
            $('#productPrice').val(data.price);
            arrayDataImages.forEach(function (image, _) {
                let htmlImage = `
                        <div class="col-md-3">
                            <img src="/storage/${image}" style="width:100%;height:200px;object-fit:cover">
                        </div>
                    `;
                rowImages.append(htmlImage);
            });
            $('#imageProductPreviewContainer').html(rowImages);
            $('#productImage').val('');

            $('#titleImage').text('Thay đổi hình ảnh (tối đa 4)');

            sizes.forEach(function (size, index) {
                let htmlSize = `
                    <div class="row">
                        <span class="removeSize text-end mt-2" style="cursor:pointer;color:red"><i class="fa-solid fa-circle-xmark"></i></span>
                        <div class="col-md-6">
                            <label for="productSize" class="form-label">Size<span
                                    class="text-danger">*</span></label>
                            <select name="sizes[]" class="form-select" id="productSize">
                                <option ${size == 'S' ? 'selected' : ''} value="S">S</option>
                                <option ${size == 'M' ? 'selected' : ''} value="M">M</option>
                                <option ${size == 'L' ? 'selected' : ''} value="L">L</option>
                                <option ${size == 'XL' ? 'selected' : ''} value="XL">XL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="productQuantity" class="form-label">Số lượng<span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="number" value="${quantities[index]}" name="quantity[]">
                        </div>
                    </div>
                    `;
                rowSizeQuantityContainer.append(htmlSize);
            });
            $('#sizeAndQuantityFields').html(rowSizeQuantityContainer);

            $('#productDescription').val(data.description);
            $('#productSku').val(data.sku);
            $('#category').val(data.category_id);
            $('#cbStatusProduct').prop('checked', data.status == 1);
            $('#titleCategoryModal').html('Cập nhật sản phẩm');
        } else {
            $('#productId').val('');
            $('#productName').val('');
            $('#productPrice').val('');
            $('#productQuantity').val('');

            $('#titleImage').text('Chọn ảnh (tối đa 4)');
            $('#productImage').val('');
            arrayImagesUpload = [];

            $('#sizeAndQuantityFields').html(
                `<div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="productSize" class="form-label">Size<span
                                    class="text-danger">*</span></label>
                            <select name="sizes[]" class="form-select" id="productSize">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="productQuantity" class="form-label">Số lượng<span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="number" value="1" name="quantity[]">
                        </div>
                    </div>
                </div>`
            );

            $('#productDescription').val('');
            $('#productSku').val('');
            $('#sizes').val('');
            $('#imageProductPreviewContainer').html('');
            $('#cbStatusProduct').prop('checked', true);
            $('#titleCategoryModal').html('Tạo sản phẩm mới');
        }
    });
});

