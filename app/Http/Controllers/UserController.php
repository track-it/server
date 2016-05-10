<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;

use Trackit\Models\User;
use Trackit\Models\Role;

class UserController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     *
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
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
     * Display the user making the request
     *
     * @return \Illuminate\Http\Response
     */
    public function self(Request $request)
    {
        $this->user->load(['proposals', 'role', 'teams']);

        return Response::json($this->user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['proposals', 'role', 'teams']);

        return Response::json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
