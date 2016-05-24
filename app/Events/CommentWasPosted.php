<?php

namespace Trackit\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Trackit\Events\Event;
use Trackit\Models\Comment;

class CommentWasPosted extends Event
{
    use SerializesModels;

    /**
     * @var
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
