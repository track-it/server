<?php

namespace Trackit\Http\Controllers;

use Auth;
use Response;
use Trackit\Models\Tag;
use Trackit\Models\User;
use Trackit\Http\Requests;
use Trackit\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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
     * Display a listing of all proposals. Which proposals returned are
     * decided by the level of access a user has. Also includes any proposals
     * that you have authored.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $statuses = $this->user->role->accessTo('global:proposal:list');
        // If searching for projects
        if ($request->has('search')) {
            $allProposals = Proposal::search($request->search, $this->user, $statuses);

        } else {
            // Get all proposals with statuses you have access to
            $proposals = Proposal::whereIn('status', $statuses)->get();

            // Get all of your own proposals
            $ownProposals = $this->user->proposals;

            // Merge collections
            $allProposals = $proposals->merge($ownProposals);
        }
        //Sort on created at
        $allProposals = $allProposals->sortByDesc(function ($proposal) {
            return $proposal->created_at;
        });

        // Create a paginator
        $paginator = $this->simplePaginate($allProposals, 20);

        return Response::json($paginator);
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
            $newTag = Tag::firstOrCreate(['name' => $tag['name']]);
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

        if ($request->tags) {
            $newTags = [];
            foreach ($request->tags as $tag) {
                $newTag = Tag::firstOrCreate(['name' => $tag['name']]);
                $newTags[] = $newTag->id;
            }
            $proposal->tags()->sync($newTags);
        }

        $proposal->load(['tags', 'attachments']);

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
