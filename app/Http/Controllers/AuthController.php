<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;
use URL;

use Trackit\Models\User;
use Trackit\Http\Requests\LoginRequest;
use Saml2;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
    	$credentials = $this->getCredentials($request);

    	if (Auth::guard('web')->attempt($credentials)) {
            return Response::json(User::byUsername($credentials['username'])->first());
        } else {
        	return response('Unauthorized.', 401);
        }
    }

    public function saml(Request $request)
    {
	return Saml2::login($request->input('callback'), [ 'key' => 'asdasdasd' ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('username', 'password');
    }
}
