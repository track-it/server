<?php

namespace Trackit\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Aacotroneo\Saml2\Saml2Auth;
use Illuminate\Routing\Controller;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;

class Saml2Controller extends Controller
{
    /**
     * @var \Aacotroneo\Saml2\Saml2Auth
     */
    protected $saml2Auth;

    /**
     * @param Saml2Auth $saml2Auth injected.
     */
    public function __construct(Saml2Auth $saml2Auth)
    {
        $this->saml2Auth = $saml2Auth;
    }


    /**
     * Generate local sp metadata
     *
     * @return \Illuminate\Http\Response
     */
    public function metadata()
    {

        $metadata = $this->saml2Auth->getMetadata();

        return response($metadata, 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * Process an incoming saml2 assertion request. Fires
     * 'saml2.loginRequestReceived' event if a valid user is
     * found.
     *
     * @return \Illuminate\Http\Response
     */
    public function acs()
    {
        $errors = $this->saml2Auth->acs();

        if (!empty($errors)) {
            logger()->error('Saml2 error', $errors);
            session()->flash('saml2_error', $errors);
            return redirect(config('saml2_settings.errorRoute'));
        }

        $user = $this->saml2Auth->getSaml2User();
        event(new Saml2LoginEvent($user));

        $redirectUrl = $user->getIntendedUrl();

        if ($redirectUrl !== null) {
            return redirect($redirectUrl . '?api_token=' . Auth::guard('web')->user()->api_token);
        }

        return redirect(config('saml2_settings.loginRoute'));
    }

    /**
     * Process an incoming saml2 logout request. Fires
     * 'saml2.logoutRequestReceived' event if its valid.
     * This means the user logged out of the SSO infrastructure,
     * you 'should' log him out locally too.
     *
     * @return \Illuminate\Http\Response
     */
    public function sls()
    {
        $error = $this->saml2Auth->sls(config('saml2_settings.retrieveParametersFromServer'));
        if (!empty($error)) {
            throw new \Exception("Could not log out");
        }

        $user = $this->saml2Auth->getSaml2User();
        $redirectUrl = $user->getIntendedUrl();

        if ($redirectUrl !== null) {
            return redirect($redirectUrl);
        }

        return redirect(config('saml2_settings.logoutRoute')); //may be set a configurable default
    }

    /**
     * Initiate a logout request across all
     * the SSO infrastructure.
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $returnTo = $request->query('returnTo');
        $sessionIndex = $request->query('sessionIndex');
        $nameId = $request->query('nameId');

        //will actually end up in the sls endpoint
        $this->saml2Auth->logout($returnTo, $nameId, $sessionIndex);
    }


    /**
     * Initiate a logout request.
     *
     * @return void
     */
    public function login()
    {
        $this->saml2Auth->login(config('saml2_settings.loginRoute'));
    }
}
