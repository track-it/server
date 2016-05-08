<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;
use URL;

use Trackit\Models\User;
use Trackit\Http\Requests\LoginRequest;
use Trackit\Http\Requests\CreateUserRequest;
use Trackit\Http\Requests\CheckTokenRequest;
use Saml2;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $this->getCredentials($request);

        if (!User::byUsername($credentials['username'])->first()) {
            return Response::json(['error' => 'Unknown username.'], 401);
        }

        if (Auth::guard('web')->attempt($credentials)) {
            return Response::json(User::byUsername($credentials['username'])->first());
        } else {
            return Response::json(['error' => 'Incorrect password.'], 401);
        }
    }

    /**
     *
     */
    public function register(CreateUserRequest $request)
    {
        $credentials = $this->getCredentials($request);

        if (User::byUsername($credentials['username'])->first()) {
            return Response::json(['error' => 'User already exists.'], 422);
        }

        $user = User::create($credentials);

        return Response::json($user);
    }

    /**
     *
     */
    public function check(CheckTokenRequest $request)
    {
        $user = User::byUsername($request->username)->first();

        if ($user && $user->api_token == $request->api_token) {
            return Response::json(['valid' => true]);
        } else {
            return Response::json(['valid' => false]);
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
