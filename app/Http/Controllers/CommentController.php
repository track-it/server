<?php

namespace Trackit\Http\Controllers;

use Response;
use Trackit\Models\User;
use Trackit\Http\Requests;
use Trackit\Models\Comment;
use Illuminate\Http\Request;
use Trackit\Models\Proposal;
use Trackit\Contracts\Commentable;
use Trackit\Http\Requests\CreateCommentRequest;
use Trackit\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * Create the controller.
     *
     * @param  \Trackit\Models\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of all comments on the given
     * commentable model.
     *
     * @param  \Trackit\Contracts\Commentable  $commentable
     * @return \Illuminate\Http\Response
     */
    public function index(Commentable $commentable)
    {
        $comments = $commentable->comments;

        return Response::json($comments);
    }

    /**
     * Return a JSON response of the request comment.
     *
     * @param  \Trackit\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return Response::json($comment);
    }

    /**
     * Store a comment for the given commentable model.
     *
     * @param  \Trackit\Contracts\Commentable  $commentable
     * @param  \Trackit\Http\Requests\CreateCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Commentable $commentable, CreateCommentRequest $request)
    {
        $comment = Comment::create([
            'body' => $request->body,
            'author_id' => $this->user->id,
            'commentable_id' => $commentable->getId(),
            'commentable_type' => get_class($commentable),
        ]);

        $comment->load('author');

        return Response::json($comment);
    }

    /**
     * Update the given comment.
     *
     * @param  \Trackit\Models\Comment  $comment
     * @param  \Trackit\Http\Requests\UpdateCommentRequest  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Comment $comment, UpdateCommentRequest $request)
    {
        $data = $request->all();
        $comment->update($data);

        return Response::json($comment);
    }

    /**
     * Delete the given comment.
     *
     * @param  \Trackit\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response('', 204);
    }
}
