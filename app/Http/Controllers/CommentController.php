<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    /**
     * @param CommentRequest $request
     * @return Comment
     */
    public function store(CommentRequest $request): Comment
    {
        $comment = new Comment($request->validated());

        $comment->article()
            ->associate($request->getArticleId())
            ->author()
            ->associate($request->user())
            ->save();

        return $comment;
    }
}
