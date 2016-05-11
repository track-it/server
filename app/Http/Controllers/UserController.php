<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\User;
use Trackit\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var \Trackit\Models\User
     */
    protected $user;

    /**
     * Create a UserController object.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Return a JSON response listing all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        if ($request->input('role')) {
            $where['role_id'] = Role::byName($request->input('role'))->first()->id;
        }

        $users = User::where($where)->orderBy('username', 'ASC')->paginate(20);

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
        $this->user->load(['proposals', 'role', 'teams']);

        if ($user = Auth::user()) {
            $user->load(['proposals', 'role', 'teams', 'projectUsers']);
        }

        return Response::json($this->user);
    }

    /**
     * Display a JSON representation of a user.
     *
     * @param  \Trackit\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['proposals', 'role', 'teams']);

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
