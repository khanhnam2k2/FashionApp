<!-- Modal Update Status Order -->
<div class="modal fade" id="updateStatusOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleUpdateStatus">Update status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_status">
                    <input type="hidden" name="orderId">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h5>Order Id: <span id="orderId"></span></h5>
                        </div>
                        <div class="col-md-8">
                            <h5>Orderer: <span id="orderer"></span></h5>
                        </div>
                    </div>
                    <div class="mb-4">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="statusOrder" class="form-label">Status Order<span
                                    class="text-danger">*</span></label>
                            <select name="status" class="form-select" id="statusOrder">
                                <option value="1">Wait for confirmation</option>
                                <option value="2">Confirmed successfully</option>
                                <option value="3">Delivering</option>
                                <option value="4">Successful delivery</option>
                                <option value="0">Cancel Order</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button id="btnUpdateStatus" type="button" class="btn btn-primary">Update
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Update status order
     * @param {formData} data data status to update status order
     * @param {Element} btn btn update status
     */
    function updateStatusOrder(data, btn) {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.order.updateStatus') }}",
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
        }).done(function(res) {
            const data = res.data.original;
            if (data.success) {
                notiSuccess(data.success);
                searchOrderAdmin();
                $('#updateStatusOrderModal').modal('toggle');
            } else {
                notiError(data.error);
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
        }).always(function() {
            btn.prop('disabled', false);
        })
    }

    $(document).ready(function() {

        // click to update status order
        $('#btnUpdateStatus').click(function(e) {
            e.preventDefault();
            const btnUpdate = $(this);
            btnUpdate.prop('disabled', true);
            let formData = new FormData($('form#form_update_status')[0]);
            showConfirmDialog('Are you sure you want to update the status of this order?', function() {
                updateStatusOrder(formData, btnUpdate);
            });

        })
        // event show update status order modal
        $('#updateStatusOrderModal').on('shown.bs.modal', function(e) {
            const data = $(e.relatedTarget).data('item');
            if (data) {
                const dataStatus = data.status;
                $('#statusOrder option').each(function() {
                    if ($(this).val() == dataStatus) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                })
                $('input[name="orderId"]').val(data.id);
                $('#orderId').text(data.id);
                $('#orderer').text(data.full_name);
                $('#titleUpdateStatus').html('Update status order');
            } else {
                notiError();
            }
        });
    })
</script>
