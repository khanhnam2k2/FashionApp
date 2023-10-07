<!-- Modal -->
<div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCategoryModal">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_category">
                    <input type="hidden" name="categoryId" id="categoryId">
                    <div class="mb-4">
                        <label for="categoryName" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name">
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <label class="col-md-3 control-label">
                            <b>Status<span class="text-danger">*</span></b>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status" checked="true" id="cbStatusCate">
                            <span class="labels" data-on="ON" data-off="OFF"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button id="btnSaveCategory" type="button" onclick="doSubmitCategory()" class="btn btn-primary">Save
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Submit form cateogry
     */
    function doSubmitCategory() {
        if ($('#categoryId').val() == '') {
            showConfirmDialog('Are you sure you want to create this category?', function() {
                createCategory();
            });
        } else {
            showConfirmDialog('Are you sure you want to update this category?', function() {
                updateCategory();
            });
        }
    }

    /**
     * Create form cateogry
     */
    function createCategory() {
        $.ajax({
            type: "POST",
            url: "{{ route('category.create') }}",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                name: $('#categoryName').val(),
                status: $('#cbStatusCate').is(':checked') ? 1 : 0,
            }),

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Category created successfully');
                searchCategory();
                $('#updateCategoryModal').modal('toggle');

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
     * Update form cateogry
     */
    function updateCategory() {
        $.ajax({
            type: "POST",
            url: "{{ route('category.update') }}",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                id: $("#categoryId").val(),
                name: $('#categoryName').val(),
                status: $('#cbStatusCate').is(':checked') ? 1 : 0,
            }),

        }).done(function(res) {
            if (res == 'ok') {
                notiSuccess('Category updated successfully');
                searchCategory();
                $('#updateCategoryModal').modal('toggle');

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

        // event show category modal
        $('#updateCategoryModal').on('shown.bs.modal', function(e) {
            var data = $(e.relatedTarget).data('item');
            if (data) {
                $("#categoryId").val(data.id);
                $("#categoryName").val(data.name);
                $('#cbStatusCate').prop('checked', data.status == 1);
                $('#titleCategoryModal').html('Update category');
            } else {
                $("#categoryId").val("");
                $("#categoryName").val("");
                $('#cbStatusCate').prop('checked', true);
                $('#titleCategoryModal').html('Create new category');
            }
        });
    })
</script>
