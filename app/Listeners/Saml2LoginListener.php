<?php

namespace Trackit\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use Log;

use Trackit\Models\User;

class Saml2LoginListener
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
     * @param  Saml2LoginEvent  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        $user = $event->getSaml2User();
	$data = [
	    'username' => $user->getUserId(),
	];
	$laravelUser = User::firstOrCreate($data);
	$res = Auth::guard('web')->loginUsingId($laravelUser->id);
	Log::info($res);
    }
}
