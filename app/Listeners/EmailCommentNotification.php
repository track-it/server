<?php

namespace Trackit\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

use Trackit\Events\CommentWasPosted;

class EmailCommentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentWasPosted  $event
     * @return void
     */
    public function handle(CommentWasPosted $event)
    {
        $comment = $event->comment;

        $data = [
            'author' => $comment->author->displayname,
            'url' => $comment->commentable->url,
        ];

        $recipients = $comment->commentable->getNotificationRecipients($comment->author);

        Mail::queue('emails.notifications.comment', $data, function ($m) use ($recipients) {
            $m->from('notifications@trackit', 'Trackit Notifications');

            $m->to($recipients)->subject('Comment was posted!');
        });
    }
}
