<?php

namespace Trackit\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Trackit\Events\CommentWasPosted' => [
            'Trackit\Listeners\EmailCommentNotification',
        ],
        'Aacotroneo\Saml2\Events\Saml2LoginEvent' => [
            'Trackit\Listeners\Saml2LoginListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
