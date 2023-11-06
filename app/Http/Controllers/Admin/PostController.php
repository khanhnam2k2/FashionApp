<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    /**
     * This is the constructor declaration.
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Show post page admin
     * @return view post list management page
     */
    public function index()
    {
        return view('admin.post.index');
    }

    /**
     * Show post table admin
     * @param Request $request
     * @return view post table
     */
    public function search(Request $request)
    {
        $data = $this->postService->searchPost($request->searchName);
        return view('admin.post.table', ['data' => $data]);
    }

    /**
     * Create new post 
     * @param StorePostRequest $request 
     * @return response ok
     */
    public function create(StorePostRequest $request)
    {
        $this->postService->createPost($request);
        return response()->json('ok');
    }

    /**
     * Update post 
     * @param UpdatePostRequest $request 
     * @return response ok
     */
    public function update(UpdatePostRequest $request)
    {
        $this->postService->updatePost($request);
        return response()->json('ok');
    }

    /**
     * Delete post 
     * @param number $id id of post 
     * @return response ok
     */
    public function delete($id)
    {
        $this->postService->deletePost($id);
        return response()->json('ok');
    }
}
