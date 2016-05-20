<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Events\StatusWasChanged;
use Trackit\Listeners\EmailStatusNotification;
use Trackit\Models\Proposal;
use Trackit\Models\User;

class EmailStatusNotificationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function testHandleMethod()
    {
        $proposal = factory(Proposal::class)->create();
        $user = factory(User::class)->create();

        $listener = new EmailStatusNotification();

        $listener->handle(new StatusWasChanged($user, $proposal));
    }
}
