<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;
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
    public function searchPost($searchName = null, $paginate = 4)
    {
        try {
            $posts = Post::select('posts.*');
            if ($searchName != null && $searchName != '') {
                $posts->where('posts.title', 'LIKE', '%' . $searchName . '%');
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
            $uploadImage = $this->uploadFile($request->file('image'), 'posts');
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
                $uploadImage = $this->uploadFile($request->file('image'), 'posts');
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
}
