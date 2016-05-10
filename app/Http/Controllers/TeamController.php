<?php

namespace Trackit\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Response;

use Trackit\Http\Requests\CreateTeamRequest;
use Trackit\Http\Requests\UpdateTeamRequest;
use Trackit\Models\Team;
use Trackit\Models\Proposal;
use Trackit\Models\User;

class TeamController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
