 <!-- Modal Comment Product -->
 <div class="modal fade" id="modalCommentProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="titleComment">Leave A Comment</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form id="form_comment_product">
                     @csrf
                     <div class="row">
                         <input type="hidden" name="commentId" id="commentId">
                         <input type="hidden" name="commentType" value="product">
                         <input type="hidden" name="productId" value="{{ $product->id }}">
                         <div class="col-lg-12">
                             <div class="d-flex flex-column">
                                 <textarea name="content" class="form-control" placeholder="Comment"></textarea>
                                 <label for="file">📸</label>
                                 <input type="file" class="form-control d-none" id="file" name="file">
                                 <input type="hidden" name="fileOld">
                             </div>
                         </div>
                         <div class="position-relative mt-2">
                             <div id="previewFileCommentProduct">
                             </div>
                             <span id="deleteFileCommentProduct"
                                 style="display: none;cursor:pointer;margin-left:20px"><i
                                     class="fa fa-close"></i></span>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                 <button id="btn-comment-product" type="button" class="btn btn-dark">Post Review</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Blog Details Section End -->
