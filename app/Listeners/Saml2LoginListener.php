<?php

namespace Trackit\Listeners;

use Log;
use Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;

use Trackit\Models\User;
use Trackit\Models\Role;

class Saml2LoginListener
{
    const CLAIM_UPN = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/upn";
    const CLAIM_EMAIL_ADDRESS = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress";
    const CLAIM_FIRST_NAME = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname";
    const CLAIM_LAST_NAME = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname";
    const CLAIM_FULL_NAME = "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name";
    const CLAIM_ROLE = "http://schemas.microsoft.com/ws/2008/06/identity/claims/role";

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
        $attributes = $user->getAttributes();
        $data = [
            'username'      => $user->getNameId(),
            'displayname'   => $this->getClaimOrDefault($attributes, self::CLAIM_FULL_NAME),
            'email'         => $this->getClaimOrDefault($attributes, self::CLAIM_EMAIL_ADDRESS),
            'role_id'       => Role::byName($this->getClaimOrDefault($attributes, self::CLAIM_ROLE, 'customer'))->first()->id,
            'type'          => User::ADFS,
            'session_index' => $user->getSessionIndex(),
        ];
        $laravelUser = User::byUsername($user->getNameId())->first();
        if (!$laravelUser) {
            $laravelUser = User::firstOrCreate($data);
        } else {
            $laravelUser->session_index = $user->getSessionIndex();
            $laravelUser->save();
        }
        $res = Auth::guard('web')->loginUsingId($laravelUser->id);
        Log::info($res);
    }

    private function getClaimOrDefault($attributes, $claimKey, $default = 'N/A')
    {
        if (array_key_exists($claimKey, $attributes)) {
            return $attributes[$claimKey][0];
        }
        return $default;
    }
}
