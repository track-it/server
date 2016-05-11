<?php

namespace Trackit\Http\Controllers;

use Response;
use Trackit\Models\Team;
use Trackit\Models\User;
use Illuminate\Http\Request;
use Trackit\Models\Proposal;
use Trackit\Http\Requests\CreateTeamRequest;
use Trackit\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{
    /**
     * @var \Trackit\Models\User
     */
    protected $user;

    /**
     * Create a TeamController object.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Return a JSON response for all teams related to
     * the given proposal model.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal, Request $request)
    {
        $query = $proposal->teams();
        if ($request->input('user_id')) {
            $user_id = $request->input('user_id');
            $query = $proposal->teams()->whereHas('users', function ($q) use ($user_id) {
                $q->where('id', '=', $user_id);
            });
        }

        return Response::json($query->get()->load('users'));
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

        $user_ids = $request->user_ids ? $request->user_ids : [];

        foreach ($user_ids as $user_id) {
            $team->users()->attach($user_id);
        }
        $team->users()->attach($this->user->id);
        $team->proposal()->associate($proposal);

        $team->save();

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
