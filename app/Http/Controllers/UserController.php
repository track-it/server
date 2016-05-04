<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;

use Trackit\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('username', 'ASC')->paginate(20);

        return Response::json($users);
    }

    /**
     * Display the user making the request
     *
     * @return \Illuminate\Http\Response
     */
    public function self(Request $request)
    {
        $user = Auth::user();
        $user->load(['proposals', 'role', 'teams', 'projects']);

        return Response::json($user);
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
        $user->load(['proposals', 'role', 'teams', 'projects']);

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
