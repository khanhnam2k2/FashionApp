<!-- Modal Update Status Order -->
<div class="modal fade" id="updateStatusOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleUpdateStatus">Cập nhật trạng thái đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_status">
                    <input type="hidden" name="orderId">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Mã đơn hàng: <span class="text-primary" id="orderCode"></span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5>Người đặt: <span class="text-primary"id="orderer"></span></h5>
                        </div>
                    </div>
                    <div class="mb-4">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="statusOrder" class="form-label">Trạng thái đơn hàng<span
                                        class="text-danger">*</span></label>
                                <select name="status" class="form-select" id="statusOrder">
                                    <option value="1">Chờ xác nhận</option>
                                    <option value="2">Xác nhận thành công</option>
                                    <option value="3">Đang giao</option>
                                    <option value="4">Giao hàng thành công</option>
                                    <option value="0">Hủy đơn hàng</option>
                                </select>
                            </div>
                            <div class="mb-3" id="cancellationReasonGroup" style="display: none;">
                                <label class="form-label" for="cancellationReason">Lý do hủy<span
                                        class="text-danger">*</span></label>
                                <textarea id="cancellationReason" name="cancellationReason" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                <button id="btnUpdateStatus" type="button" class="btn btn-primary">Cập nhật
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
    function updateStatusOrder(btn) {
        let data = new FormData($('form#form_update_status')[0]);
        showConfirmDialog('Bạn có chắc chắn muốn cập nhật trạng thái của đơn hàng này không?',
            function() {
                btn.text('Đang cập nhật...');
                btn.prop('disabled', true);
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
                    btn.text('Cập nhật');
                    btn.prop('disabled', false);
                })
            });

    }

    $(document).ready(function() {

        // Click to update status order
        $('#btnUpdateStatus').click(function(e) {
            e.preventDefault();
            updateStatusOrder($(this));

        });

        // Press enter to update status order
        $('#updateStatusOrderModal').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const btnUpdateStatus = $('#btnUpdateStatus');
                updateStatusOrder(btnUpdateStatus);
            }
        });

        // Event show update status order modal
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
                $('#orderCode').text(data.code);
                $('#orderer').text(data.full_name);
            } else {
                notiError();
            }
        });


        // Show textarea messsage cancel when status order = 0
        $('#statusOrder').change(function() {
            let selectValue = $(this).val();
            if (selectValue === "0") {
                $("#cancellationReasonGroup").show();
            } else {
                $("#cancellationReasonGroup").hide();
            }
        })
    })
</script>
