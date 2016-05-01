<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;

use Trackit\Models\User;
use Trackit\Http\Requests\LoginRequest;

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
