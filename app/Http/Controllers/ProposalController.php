<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Auth;

use Trackit\Http\Requests;
use Trackit\Http\Requests\CreateProposalRequest;
use Trackit\Http\Requests\UpdateProposalRequest;
use Trackit\Models\Proposal;
use Trackit\Models\Tag;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     *
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal)
    {
        $proposal->load(['attachments', 'tags']);
        return Response::json($proposal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Proposal $proposal, UpdateProposalRequest $request)
    {
        $proposal->update($request->all());
        return Response::json($proposal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return response('', 204);
    }
}
