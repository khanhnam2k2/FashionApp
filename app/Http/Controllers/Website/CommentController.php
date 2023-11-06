<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    /**
     * This is the constructor declaration.
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Show comment post table 
     * @param Request $request
     * @return view comment post table
     */
    public function searchCommentPost(Request $request)
    {
        $data = $this->commentService->searchComment($request->typeComment);
        return view('website.post.listComment', ['data' => $data]);
    }

    /**
     * Show comment product table 
     * @param Request $request
     * @return view comment product table
     */
    public function searchCommentProduct(Request $request)
    {
        $data = $this->commentService->searchComment($request->typeComment);
        return view('website.shop.listComment', ['data' => $data]);
    }

    /**
     * Create new comment 
     * @param StoreCommentRequest $request 
     * @return response ok
     */
    public function create(StoreCommentRequest $request)
    {
        $data = $this->commentService->createComment($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Update comment 
     * @param Request $request 
     * @return response ok
     */
    public function update(Request $request)
    {
        $data = $this->commentService->updateComment($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Delete comment 
     * @param number $id id of comment 
     * @return response ok
     */
    public function delete($id)
    {
        $data = $this->commentService->deleteComment($id);
        return response()->json(['data' => $data]);
    }
}
