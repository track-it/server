<?php

namespace Trackit\Http\Controllers;

use Response;
use Trackit\Models\Team;
use Illuminate\Http\Request;
use Trackit\Models\Proposal;
use Trackit\Http\Requests\CreateTeamRequest;
use Trackit\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{
    /**
     * Return a JSON response for all teams related to
     * the given proposal model.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
        return Response::json($proposal->teams->load('users'));
    }

    /**
     * Create a new team for the given proposal.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @param  \Trackit\Http\Requests\CreateTeamRequest  $request
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
     * Update the given team.
     *
     * @param  \Trackit\Models\Team  $team
     * @param  \Trackit\Http\Requests\UpdateTeamRequest  $request
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
     * Delete the given team.
     *
     * @param  \Trackit\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response('', 204);
    }
}
