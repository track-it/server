<?php

namespace Trackit\Listeners;

use Mail;
use Trackit\Events\StatusWasChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailStatusNotification
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
     * @param  StatusWasChanged  $event
     * @return void
     */
    public function handle(StatusWasChanged $event)
    {
        $model = $event->model;
        $user = $event->user;

        $data = [
            'user'      => $user->displayname,
            'status'    => $model->status,
            'url'       => $model->url,
        ];

        $recipients = $model->getNotificationRecipients($user);

        $emails = $recipients
            ->map(function ($recipient) {
                return $recipient->email;
            })
            ->toArray();

        Mail::queue('emails.notifications.status', $data, function ($m) use ($emails) {
            $m->from('notifications@trackit', 'Trackit Notifications');

            $m->to($emails)->subject('Status was changed!');
        });
    }
}
