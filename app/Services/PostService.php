<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService extends BaseService
{

    public function getLimitPost()
    {
        try {
            $posts = Post::where('status', Status::ON)->take(3)->latest()->get();
            return $posts;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
    public function searchPost($searchName = null, $paginate = 4, $status = null)
    {
        try {
            $posts = Post::select('posts.*');
            if ($searchName != null && $searchName != '') {
                $posts->where('posts.title', 'LIKE', '%' . $searchName . '%');
            }
            if ($status != null && $status != '') {
                $posts->where('posts.status', Status::ON);
            }
            $posts = $posts->latest()->paginate($paginate);
            return $posts;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function createPost($request)
    {
        try {
            $uploadImage = $this->uploadImage($request->file('image'), 'posts');
            $post = [
                'title' => $request->title,
                'image' => $uploadImage,
                'content' => $request->contentPost,
                'user_id' => Auth::user()->id,
                'status' => $request->statusPost,
            ];
            $data = Post::create($post);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updatePost($request)
    {
        try {
            $data = Post::findOrFail($request->postId);
            if (!empty($request->file('image'))) {
                $this->deleteFile($data->image);
                $uploadImage = $this->uploadImage($request->file('image'), 'posts');
            }
            $post = [
                'title' => $request->title,
                'image' => $uploadImage ?? $data->image,
                'content' => $request->contentPost,
                'user_id' => Auth::user()->id,
                'status' => $request->statusPost,
            ];
            $data = $data->update($post);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function deletePost($id)
    {
        try {
            $data = Post::findOrFail($id);

            $this->deleteFile($data->image);
            $data->delete();
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function getPostById($id)
    {
        try {
            $data = Post::select(
                'posts.id',
                'posts.title',
                'posts.content',
                'posts.image',
                'posts.created_at',
                'users.name as author',
                DB::raw('COUNT(comments.post_id) as commentCount')
            )
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->join('comments', 'comments.post_id', '=', 'posts.id')
                ->where('status', Status::ON)->where('posts.id', $id)
                ->groupBy('posts.id', 'posts.title', 'posts.content', 'posts.image', 'posts.created_at', 'users.name')
                ->first();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
