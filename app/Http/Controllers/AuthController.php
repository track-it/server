<?php

namespace Trackit\Http\Controllers;

use URL;
use Auth;
use Saml2;
use Response;
use Trackit\Models\User;
use Illuminate\Http\Request;
use Trackit\Http\Requests\LoginRequest;
use Trackit\Http\Requests\CheckTokenRequest;
use Trackit\Http\Requests\CreateUserRequest;
use Trackit\Models\Role;

class AuthController extends Controller
{
    /**
     * Attempts to authenticate a user.
     *
     * @param  \Trackit\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $this->getCredentials($request);

        if (!User::byUsername($credentials['username'])->first()) {
            return Response::json(['error' => 'Unknown username.'], 401);
        }

        if (Auth::guard('web')->attempt($credentials)) {
            return Response::json(User::byUsername($credentials['username'])->first()->withApiToken());
        } else {
            return Response::json(['error' => 'Incorrect password.'], 401);
        }
    }

    /**
     * Attempts to register a user.
     *
     * @param  \Trackit\Http\Requests\CreateUserRequest
     * @return \Illuminate\Http\Response
     */
    public function register(CreateUserRequest $request)
    {
        $credentials = $request->only('username', 'password', 'email', 'displayname');

        if (User::byUsername($credentials['username'])->first()) {
            return Response::json(['error' => 'User already exists.'], 422);
        }

        $user = User::create($credentials);
        $user->role()->associate(Role::byName('customer')->first())->save();
        $user->confirmed = false;
        $user->type = User::LOCAL;
        $user->save();

        return Response::json($user->withApiToken());
    }

    /**
     * Validates an api token.
     *
     * @param  \Trackit\Http\Requests\CheckTokenRequest  $request
     * @return \Illuminate\Http\Response
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

    /**
     * Attempts to log a user in through Saml2.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saml(Request $request)
    {
        return Saml2::login($request->input('callback'), [ 'key' => 'asdasdasd' ]);
    }

    /**
     *
     */
    public function samlLogout(Request $request)
    {
        $returnTo = $request->input('callback');
        $user = User::where('api_token', '=', $request->input('api_token'))->first();
        $nameId = $user->username;
        $sessionIndex = $user->session_index;

        //dd(compact('returnTo', 'nameId', 'sessionIndex'));
        
        Saml2::logout($returnTo, $nameId, $sessionIndex);
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
