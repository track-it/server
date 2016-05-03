<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use Trackit\Http\Requests\CreateTeamRequest;
use Trackit\Http\Requests\UpdateTeamRequest;
use Trackit\Models\Team;
use Trackit\Models\Proposal;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
        return Response::json($proposal->teams->load('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Proposal $proposal, CreateTeamRequest $request)
    {
        $team = Team::create();

        $team->users()->attach($request->user_ids);

        $team->proposal()->associate($proposal);

        $team->load('users');

        return Response::json($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return Response::json($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Team $team, UpdateTeamRequest $request)
    {
        if ($request->users) {
            foreach ($request->users as $user) {
                $team->users()->attach($user);
            }
        }

        return Response::json($team->load('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response('', 204);
    }
}
