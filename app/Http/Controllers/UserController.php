<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return a JSON response listing all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('username', 'ASC')->paginate(20);

        return Response::json($users);
    }

    /**
     * Display a JSON response of the user making
     * the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function self(Request $request)
    {
        if ($user = Auth::user()) {
            $user->load(['proposals', 'role', 'teams', 'projectUsers']);
        }

        return Response::json($user);
    }

    /**
     * Display a JSON representation of a user.
     *
     * @param  \Trackit\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['proposals', 'role', 'teams', 'projectUsers']);

        return Response::json($user);
    }

    /**
     * Update the given user.
     *
     * @param  \Trackit\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        //
    }

    /**
     * Remove the given user.
     *
     * @param  \Trackit\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
