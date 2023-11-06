<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
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
     * Show post page in website
     * @return view post page
     */
    public function index()
    {
        return view('website.post.index');
    }

    /**
     * Show post table 
     * @param Request $request
     * @return view post table
     */
    public function search(Request $request)
    {
        $data = $this->postService->searchPost($request->searchName, $request->paginate, $request->status);
        return view('website.post.listPost', compact('data'));
    }

    /**
     * Show post details page 
     * @return view post details page
     */
    public function details($id)
    {
        $data = $this->postService->getPostById($id);
        return view('website.post.details', ['post' => $data]);
    }
}
