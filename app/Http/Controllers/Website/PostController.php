<?php

namespace App\Http\Controllers\Website;

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
        return view('website.post.index');
    }

    public function search(Request $request)
    {
        $data = $this->postService->searchPost($request->searchName, $request->paginate);
        return view('website.post.listPost', compact('data'));
    }
}
