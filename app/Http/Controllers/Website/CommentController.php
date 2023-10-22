<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function searchCommentPost(Request $request)
    {
        $data = $this->commentService->searchComment();
        return view('website.post.listComment', ['data' => $data]);
    }
    public function create(Request $request)
    {
        $data = $this->commentService->createComment($request);
        return response()->json(['data' => $data]);
    }
}
