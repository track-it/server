<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\Tag;
use Trackit\Http\Requests;
use Trackit\Models\Project;
use Illuminate\Http\Request;
use Trackit\Models\Proposal;
use Trackit\Http\Requests\DeleteRequest;
use Trackit\Http\Requests\ShowProjectRequest;
use Trackit\Http\Requests\CreateProjectRequest;
use Trackit\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Return a JSON response listing all projects. If a proposal is
     * given, lists all projects that is created from that proposal.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
        if (!$proposal->exists) {
            return Response::json(Project::orderBy('updated_at', 'desc')->paginate(20));
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
        $project->load('team.users', 'projectUsers.user', 'attachments');
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

        $tags = $request->tags == null ? [] : $request->tags;

        foreach ($tags as $id) {
            $newTag = Tag::firstOrCreate(['name' => $id]);
            $project->tags()->attach($newTag->id);
        }

        $project->load('tags');

        $project->addProjectUser('teacher', Auth::user());
        $project->proposal()->associate($proposal);
        $project->status = Project::NOT_COMPLETED;
        $project->save();

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
}
