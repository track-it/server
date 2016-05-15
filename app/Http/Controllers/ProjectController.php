<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\Tag;
use Trackit\Http\Requests;
use Trackit\Models\Project;
use Illuminate\Http\Request;
use Trackit\Models\Proposal;
use Trackit\Models\User;
use Trackit\Http\Requests\DeleteRequest;
use Trackit\Http\Requests\ShowProjectRequest;
use Trackit\Http\Requests\CreateProjectRequest;
use Trackit\Http\Requests\PublishProjectRequest;
use Trackit\Http\Requests\UpdateProjectRequest;

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
    public function index(Proposal $proposal)
    {
        if (!$proposal->exists) {
            $statuses = $this->user->role->accessTo('global:project:list');

            $proposals = Project::whereIn('status', $statuses);

            return Response::json($proposals->orderBy('updated_at', 'desc')->paginate(20));
        } else {
            return Response::json($proposal->projects()->orderBy('updated_at', 'desc')->paginate(20));
        }
    }

    /**
     * Return a JSON response for the given project.
     *
     * @param  \Trackit\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, ShowProjectRequest $request)
    {
        $project->load('participants', 'attachments', 'tags');
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

        $project->team->users->each(function ($user) use (&$project) {
            $project->addParticipant('student', $user);
        });

        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $newTag = Tag::firstOrCreate(['name' => $tag['name']]);
                $project->tags()->attach($newTag->id);
            }
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

        if ($request->tags) {
            $newTags = [];
            foreach ($request->tags as $tag) {
                $newTag = Tag::firstOrCreate(['name' => $tag['name']]);
                $newTags[] = $newTag->id;
            }
            $project->tags()->sync($newTags);
        }

        $project->load('tags');

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
}
