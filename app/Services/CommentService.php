<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentService extends BaseService
{

    public function searchComment($typeComment)
    {
        try {
            $comments = Comment::select('comments.*', 'users.name as author')
                ->join('users', 'users.id', '=', 'comments.user_id');
            if ($typeComment == 'post') {
                $comments->whereNotNull('post_id');
            } elseif ($typeComment == 'product') {
                $comments->whereNotNull('product_id');
            }
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

    public function updateComment($request)
    {
        // dd($request->all());
        try {
            $comment = Comment::findOrFail($request->commentId);

            $currentUser = Auth::user();
            if ($currentUser->id !== $comment->user_id) {
                return response()->json(['error' => 'You do not have permission to update this comment!']);
            }
            if (!empty($request->file('file')) || !empty($request->content)) {
                if (!empty($request->file())) {
                    $uploadFile = $this->uploadFile($request->file('file'), 'comments');
                    $comment->file = json_encode($uploadFile);
                } else {
                    $comment->file = $request->fileOld ?? null;
                }
                $comment->content = $request->content;
                $comment->save();
                return response()->json(['success' => 'Updated comment successfully']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function deleteComment($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $currentUser = Auth::user();
            if ($currentUser->id !== $comment->user_id && !$currentUser->role == UserRole::ADMIN) {
                return response()->json(['error' => 'You do not have permission to delete this comment!']);
            }
            if ($comment->image) {
                $this->deleteFile($comment->image);
            }
            $comment->delete();
            return response()->json(['success' => 'Delete comment successfully']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
