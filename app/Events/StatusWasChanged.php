<?php

namespace Trackit\Events;

use Trackit\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Trackit\Contracts\HasStatus;
use Trackit\Models\User;

class StatusWasChanged extends Event
{
    use SerializesModels;

    /**
     * @var Trackit\Contracts\HasStatus;
     */
    public $model;

    /**
     * @var Trackit\Models\user;
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, HasStatus $model)
    {
        $this->model = $model;
        $this->user = $user;
    }
}
