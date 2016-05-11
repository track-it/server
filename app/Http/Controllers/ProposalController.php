<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\Tag;
use Trackit\Models\User;
use Trackit\Http\Requests;
use Trackit\Models\Proposal;
use Illuminate\Http\Request;
use Trackit\Http\Requests\DeleteRequest;
use Trackit\Http\Requests\ShowProposalRequest;
use Trackit\Http\Requests\CreateProposalRequest;
use Trackit\Http\Requests\UpdateProposalRequest;

class ProposalController extends Controller
{
    /**
     * @var \Trackit\Models\User
     */
    protected $user;

    /**
     * Create a new ProposalController object.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a lising of all proposals. If the user is not logged,
     * then only approved proposals will appear.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = $this->user->role->accessTo('global:proposal:list');

        $proposals = Proposal::whereIn('status', $statuses);

        return Response::json($proposals->orderBy('created_at', 'desc')->paginate(10));
    }

    /**
     * Create a new proposal.
     *
     * @param  \Trackit\Http\Requests\CreateProposalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProposalRequest $request)
    {
        $proposal = Proposal::create($request->all());
        $proposal->status = Proposal::NOT_REVIEWED;

        $tags = $request->tags == null ? [] : $request->tags;

        foreach ($tags as $tag) {
            $newTag = Tag::firstOrCreate(['name' => $tag]);
            $proposal->tags()->attach($newTag->id);
        }

        $proposal->author()->associate($this->user);
        $proposal->save();

        $proposal->load('tags');

        return Response::json($proposal);
    }

    /**
     * Returns a JSON response for the given proposal.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal, ShowProposalRequest $request)
    {
        $proposal->load(['attachments', 'tags']);
        return Response::json($proposal);
    }

    /**
     * Update the given proposal.
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @param  \Trackit\Http\Requests\UpdateProposalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Proposal $proposal, UpdateProposalRequest $request)
    {
        $proposal->update($request->all());
        return Response::json($proposal);
    }

    /**
     * Remove the given proposal
     *
     * @param  \Trackit\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal, DeleteRequest $request)
    {
        $proposal->delete();
        return response('', 204);
    }
}
