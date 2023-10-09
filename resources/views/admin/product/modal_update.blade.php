<!-- Modal Create/Update Product -->
<div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCategoryModal">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_product">
                    <input type="hidden" name="productId" id="productId">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="productName" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productName" name="name">
                        </div>
                        <div class="col-md-4">
                            <label for="productPrice" class="form-label">Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productPrice" name="price">
                        </div>
                    </div>
                    <div class="">
                        <label for="productImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="productImage" name="image">
                    </div>
                    <div class="w-100 d-flex justify-content-center my-2" id="imageProductPreviewContainer">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category<span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" id="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="productQuantity" class="form-label">Quantity<span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="productQuantity"
                                name="quantity">
                        </div>
                        <div class="col-md-3">
                            <label for="productSku" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="productSku" name="sku">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Status<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusProduct">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button id="btnSaveProduct" type="button" onclick="doSubmitProduct()" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    #imageProductPreview {
        width: 200px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
<script>
    /**
     * Submit form cateogry
     */
    function doSubmitProduct() {
        let formData = new FormData($('form#form_product')[0]);
        formData.append('statusProduct', $('#cbStatusProduct').is(':checked') ? 1 : 0)
        if ($('#productId').val() == '') {
            showConfirmDialog('Are you sure you want to create this product?', function() {
                createProduct(formData);
            });
        } else {
            showConfirmDialog('Are you sure you want to update this product?', function() {
                updateProduct(formData);
            });
        }
    }

    /**
     * Create form product
     */
    function createProduct(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('product.create') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Product created successfully');
                searchProduct();
                $('#updateProductModal').modal('toggle');
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

    }

    /**
     * Update form product
     */
    function updateProduct(data) {
        $.ajax({
            type: "POST",
            url: "{{ route('product.update') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Product updated successfully');
                searchProduct();
                $('#updateProductModal').modal('toggle');

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
    }

    $(document).ready(function() {

        // add/change image for product
        $('#productImage').on('change', function() {
            handleImageUpload(this, $('#imageProductPreview'));
        });
        // event show product modal
        $('#updateProductModal').on('shown.bs.modal', function(e) {
            var data = $(e.relatedTarget).data('item');
            let imagePreviewHtml = '';
            if (data) {
                imagePreviewHtml = `<img src="/storage/${data.image}" id="imageProductPreview" />`
                $("#productId").val(data.id);
                $("#productName").val(data.name);
                $("#productPrice").val(data.price);
                $("#productQuantity").val(data.quantity);
                $('#imageProductPreviewContainer').html(imagePreviewHtml);
                $("#productDescription").val(data.description);
                $("#productSku").val(data.sku);
                $('#category').val(data.category_id);
                $('#cbStatusProduct').prop('checked', data.status == 1);
                $('#titleCategoryModal').html('Update product');
            } else {
                imagePreviewHtml = `<img src="{{ asset('img/logo.png') }}" id="imageProductPreview" />`;
                $("#productId").val('');
                $("#productName").val('');
                $("#productPrice").val('');
                $("#productQuantity").val('');
                $("#productImage").val('');
                $("#productDescription").val('');
                $("#productSku").val('');
                $('#imageProductPreviewContainer').html(imagePreviewHtml);
                $('#cbStatusProduct').prop('checked', true);
                $('#titleCategoryModal').html('Create new product');
            }
        });
    })
</script>