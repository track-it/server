<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;

use Trackit\Http\Requests;
use Trackit\Models\Proposal;
use Trackit\Models\Comment;
use Trackit\Models\User;
use Trackit\Support\JsonResponse;
use Trackit\Contracts\Commentable;
use Trackit\Http\Requests\CreateCommentRequest;

class CommentController extends Controller
{
   	protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store(Commentable $commentable, CreateCommentRequest $request)
    {	
    	
        $comment = Comment::create([
            'body' => $request->body,
            'author_id' => $this->user->id ? $this->user->id : 0,
            'source_id' => $commentable->getId(),
            'source_type' => get_class($commentable),
        ]);


        return JsonResponse::success($comment);
    }
}
