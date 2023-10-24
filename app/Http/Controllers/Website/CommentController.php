<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
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
        $data = $this->commentService->searchComment($request->typeComment);
        return view('website.post.listComment', ['data' => $data]);
    }

    public function searchCommentProduct(Request $request)
    {
        $data = $this->commentService->searchComment($request->typeComment);
        return view('website.shop.listComment', ['data' => $data]);
    }

    public function create(StoreCommentRequest $request)
    {
        $data = $this->commentService->createComment($request);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {
        $data = $this->commentService->updateComment($request);
        return response()->json(['data' => $data]);
    }
}
