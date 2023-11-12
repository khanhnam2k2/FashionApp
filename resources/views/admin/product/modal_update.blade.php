<!-- Modal Create/Update Product -->
<div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCategoryModal">Tạo sản phẩm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_product">
                    <input type="hidden" name="productId" id="productId">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="productName" class="form-label">Tên sản phẩm<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productName" name="name">
                        </div>
                        <div class="col-md-4">
                            <label for="productPrice" class="form-label">Giá<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productPrice" name="price">
                        </div>
                    </div>
                    <div class="">
                        <div class="d-flex items-center justify-content-between">
                            <div class="d-flex items-center gap-4">
                                <h5 id="titleImage">Chọn ảnh (tối đa 4)</h5>
                                <label for="productImage" class="form-label"><i
                                        class="fa-solid fa-file-image"></i></label>
                            </div>
                            <span id="btn-delete-images" style="display: none" class="btn"><i
                                    class="fa-solid fa-circle-xmark"></i></span>
                        </div>
                        <input type="file" accept="image/*" class="form-control d-none" id="productImage" multiple
                            name="images">
                    </div>
                    <div class="w-100 my-2 mb-5" id="imageProductPreviewContainer">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="category" class="form-label">Danh mục<span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" id="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="productSku" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="productSku" name="sku">
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div id="sizeAndQuantityFields">
                                <div class="">
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
                                </div>
                            </div>
                            <button class="btn btn-info mt-2" id="addSize">Thêm size mới</button>
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="productDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="productDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Trạng thái<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusProduct">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnSubmitProduct" type="button" class="btn btn-primary">Lưu
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

    #updateProductModal {
        --bs-modal-width: 1000px !important;
    }

    .fa-file-image {
        cursor: pointer;
        font-size: 20px;
        color: rgb(76, 76, 228);
    }

    .fa-circle-xmark {
        cursor: pointer;
        font-size: 20px;
        color: red;
    }
</style>
