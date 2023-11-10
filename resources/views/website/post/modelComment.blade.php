 <!-- Modal CommentPost -->
 <div class="modal fade" id="modalCommentPost" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="titleComment">Bình luận</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form id="form_comment_post">
                     @csrf
                     <div class="row">
                         <input type="hidden" name="commentId" id="commentId">
                         <input type="hidden" name="commentType" value="post">
                         <input type="hidden" name="postId" value="{{ $post->id }}">
                         <div class="col-lg-12">
                             <div class="d-flex flex-column">
                                 <textarea name="content" class="form-control" placeholder="Nội dung"></textarea>
                                 <label for="file" style="cursor: pointer">📸</label>
                                 <input type="file" class="form-control d-none" id="file" name="file">
                                 <input type="hidden" name="fileOld">
                             </div>
                         </div>
                         <div class="position-relative mt-2">
                             <div id="previewFileCommentPost">
                             </div>
                             <span id="deleteFileCommentPost" style="display: none;cursor:pointer;margin-left:20px"><i
                                     class="fa fa-close"></i></span>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                 <button id="btn-comment-post" type="button" class="btn btn-dark">Đăng</button>
             </div>
         </div>
     </div>
 </div>
 <!-- Blog Details Section End -->
