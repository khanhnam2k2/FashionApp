 <!-- Modal Comment Product -->
 <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="titleComment">Lý do hủy </h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form id="form_cancel_order">
                     @csrf
                     <div class="row">
                         <input type="hidden" name="orderId" id="orderId">

                         <div class="col-lg-12">
                             <div class="d-flex flex-column">
                                 <textarea name="cancellationReason" id="cancellationReason" class="form-control" placeholder="nhập lý do hủy đơn hàng"></textarea>
                             </div>
                         </div>

                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn" data-dismiss="modal">Không phải bây giờ</button>
                 <button id="btn-cancel-order" type="button" class="btn btn-danger">Hủy</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Blog Details Section End -->

 <script>
     $(document).ready(function() {
         $(document).on('click', '#btn-cancel-order', function(e) {
             e.preventDefault();
             const orderId = $("#orderId").val();
             const cancellationReason = $("#cancellationReason").val();
             const btnCancel = $(this);
             const messageSuccess = 'Đơn hàng đã được hủy thành công!';
             showConfirmDialog(
                 'Bạn có chắc chắn muốn hủy đơn hàng này không?',
                 function() {
                     btnCancel.prop('disabled', true);
                     btnCancel.text('Đang hủy...');
                     updateStatusOrder(orderId, parseInt(statusCancelOrder), btnCancel,
                         messageSuccess, cancellationReason);
                 });
         })
         // Event show category modal
         $('#cancelOrderModal').on('shown.bs.modal', function(e) {
             const orderId = $(e.relatedTarget).data('order-id');
             if (orderId) {
                 $("#orderId").val(orderId);
             } else {
                 notiError();
             }
         });
     })
 </script>
