<?php

namespace App\Services;

use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentService extends BaseService
{

    public function searchComment()
    {
        try {
            $comments = Comment::select('comments.*', 'users.name as author')
                ->join('users', 'users.id', '=', 'comments.user_id');
            $comments = $comments->latest()->paginate(2);
            return $comments;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function createComment($request)
    {
        try {
            $commentType = $request->commentType;
            if ($commentType === 'post') {
                if (!empty($request->file('file')) || !empty($request->content)) {
                    $comment = new Comment();
                    if (!empty($request->file())) {
                        $uploadFile = $this->uploadFile($request->file('file'), 'comments');
                        $comment->file = json_encode($uploadFile);
                    }
                    $comment->user_id = Auth::user()->id;
                    $comment->post_id = $request->postId;
                    $comment->content = $request->content;
                    $comment->save();
                    return true;
                }
            } elseif ($commentType === 'product') {
                if (!empty($request->file('file')) || !empty($request->content)) {
                    $comment = new Comment();
                    if (!empty($request->file())) {
                        $uploadFile = $this->uploadFile($request->file('file'), 'comments');
                        $comment->file = json_encode($uploadFile);
                    }
                    $comment->user_id = Auth::user()->id;
                    $comment->product_id = $request->productId;
                    $comment->content = $request->content;
                    $comment->save();
                    return true;
                }
            } else {
                return null;
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
