<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\Tag;
use Trackit\Http\Requests;
use Illuminate\Http\Request;
use Trackit\Models\Proposal;
use Trackit\Http\Requests\CreateProposalRequest;
use Trackit\Http\Requests\UpdateProposalRequest;

class ProposalController extends Controller
{
    /**
     * Display a lising of all proposals. If the user is not logged,
     * then only approved proposals will appear.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Auth::guest() ? [Proposal::APPROVED] : Auth::user()->role->accessTo('proposal');
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
    public function show(Proposal $proposal)
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
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return response('', 204);
    }
}
