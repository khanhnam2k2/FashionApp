<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function index()
    {
        return view('admin.post.index');
    }

    public function search(Request $request)
    {
        $data = $this->postService->searchPost($request->searchName);
        return view('admin.post.table', ['data' => $data]);
    }

    public function create(Request $request)
    {
        $this->postService->createPost($request);
        return response()->json('ok');
    }

    public function update(Request $request)
    {
        $this->postService->updatePost($request);
        return response()->json('ok');
    }

    public function delete($id)
    {
        $this->postService->deletePost($id);
        return response()->json('ok');
    }
}
