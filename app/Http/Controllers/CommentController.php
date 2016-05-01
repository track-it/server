<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use Trackit\Http\Requests;
use Trackit\Models\Proposal;
use Trackit\Models\Comment;
use Trackit\Models\User;
use Trackit\Contracts\Commentable;
use Trackit\Http\Requests\CreateCommentRequest;
use Trackit\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
   	protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Commentable $commentable)
    {
        $comments = $commentable->comments;

        return Response::json($comments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return Response::json($comment);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Commentable $commentable, CreateCommentRequest $request)
    {	
        $comment = Comment::create([
            'body' => $request->body,
            'author_id' => $this->user->id,
            'source_id' => $commentable->getId(),
            'source_type' => get_class($commentable),
        ]);

        return Response::json($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Comment $comment, UpdateCommentRequest $request)
    {
        $data = $request->all();
        $comment->update($data);

        return Response::json($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response('', 204);
    }
}
