<?php

namespace Trackit\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Auth;
use Response;

use Trackit\Models\Tag;
use Trackit\Models\User;
use Trackit\Http\Requests;
use Trackit\Models\Project;
use Trackit\Models\Proposal;
use Trackit\Models\Team;
use Trackit\Http\Requests\DeleteRequest;
use Trackit\Http\Requests\ShowProjectRequest;
use Trackit\Http\Requests\CreateProjectRequest;
use Trackit\Http\Requests\UpdateProjectRequest;
use Trackit\Http\Requests\PublishProjectRequest;

class ProjectController extends Controller
{
    /**
     * @var \Trackit\Models\User
     */
    protected $user;

    /**
     * Create a new ProjectController object.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Return a JSON response listing all projects, filtered by user role access.
     * If a proposal is given, lists all projects that is created from that proposal.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Proposal $proposal)
    {
        $statuses = $this->user->role->accessTo('global:project:list');
        // If searching for projects
        if ($request->has('search')) {
            $allProjects = Project::search($request->search, $statuses);
        // Else list all projects
        } else if (!$proposal->exists) {
            $allProjects = Project::whereIn('status', $statuses)->get();
        } else {
            $allProjects = $proposal->projects;
        }
        // Sort descending on updated at
        $allProjects = $allProjects->sortByDesc(function ($project) {
            return $project->updated_at;
        });
        // Create a paginator
        $paginator = $this->simplePaginate($allProjects, 20);

        return Response::json($paginator);
    }

    /**
     * Return a JSON response for the given project.
     *
     * @param  \Trackit\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, ShowProjectRequest $request)
    {
        $project->load('participants', 'attachments');
        return Response::json($project);
    }

    /**
     * Creates a new project from the given proposal
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @param  \Trackit\Http\Requests\CreateProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Proposal $proposal, CreateProjectRequest $request)
    {
        $project = Project::create($request->all());

        $team = Team::find($request->team_id);

        $team->users->each(function ($user) use (&$project) {
            $project->addParticipant('student', $user);
        });
        $team->delete();

        $tags = $request->tags == null ? [] : $request->tags;

        foreach ($tags as $id) {
            $newTag = Tag::firstOrCreate(['name' => $id]);
            $project->tags()->attach($newTag->id);
        }

        $project->addParticipant('teacher', Auth::user());
        $project->proposal()->associate($proposal);
        $project->status = Project::NOT_COMPLETED;
        $project->save();

        $project->load('tags', 'participants');

        return Response::json($project);
    }

    /**
     * Update the project.
     *
     * @param  \Trackit\Models\Project  $project
     * @param  \Trackit\Http\Requests\UpdateProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, UpdateProjectRequest $request)
    {
        $project->update($request->all());
        return Response::json($project);
    }

    /**
     * Remove the project.
     *
     * @param  \Trackit\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, DeleteRequest $request)
    {
        $project->delete();
        return response('', 204);
    }

    /**
     * Publish the project.
     *
     * @param \Trackit\Models\Project $project
     * @param \Trackit\Http\Requests\PublishProjectRequest $request
     * @return \Illuminate\Http\Response
     */
    public function publish(Project $project, PublishProjectRequest $request)
    {
        $project->status = Project::PUBLISHED;
        $project->save();

        return Response::json($project);
    }

    /**
     * Get a paginator only supporting simple next and previous links.
     *
     * This is more efficient on larger data-sets, etc.
     *
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    private function simplePaginate($collection, $perPage = 15)
    {
        $page = Paginator::resolveCurrentPage();
        $spliced = $collection->splice(($page - 1) * $perPage, $perPage + 1);
        return new Paginator($spliced, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }
}
