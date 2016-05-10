<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Auth;

use Trackit\Http\Requests;
use Trackit\Models\Project;
use Trackit\Models\Proposal;
use Trackit\Models\Tag;
use Trackit\Http\Requests\ShowProjectRequest;
use Trackit\Http\Requests\UpdateProjectRequest;
use Trackit\Http\Requests\CreateProjectRequest;
use Trackit\Http\Requests\DeleteRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, ShowProjectRequest $request)
    {
        $project->load('team.users', 'attachments');
        return Response::json($project);
    }

    /**
     * Show the form for creating a new resource.
     *
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, UpdateProjectRequest $request)
    {
        $project->update($request->all());
        return Response::json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, DeleteRequest $request)
    {
        $project->delete();
        return response('', 204);
    }
}
