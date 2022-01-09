<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentRequest;
use App\Service\Comment\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    Private $CommentService;

    public function __construct(CommentService $CommentService)
    {
        $this->CommentService=$CommentService;
    }
    public function get()
    {
        return $this->CommentService->get();
    }
    public function getById($id)
    {
        return $this->CommentService->getById($id);
    }
    public function create(CommentRequest $request)
    {
        return $this->CommentService->create($request);
    }
    public function update(CommentRequest $request,$id)
    {
        return $this->CommentService->update($request,$id);
    }
    public function trash($id)
    {
        return $this->CommentService->trash($id);
    }
    public function gettrashed()
    {
        return $this->CommentService->gettrashed();
    }
    public function restoreTrashe($id)
    {
        return $this->CommentService->restoreTrashe($id);
    }
    public function delete($id)
    {
        return $this->CommentService->delete($id);
    }

    public function getOfferByCommentId($comment_id)
    {
        return $this->CommentService->getOfferByCommentId($comment_id);
    }
    public function getcomments($offer_id)
    {
        return $this->CommentService->getcomments($offer_id);
    }

}
