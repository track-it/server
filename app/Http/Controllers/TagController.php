<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use Trackit\Http\Requests\CreateTagRequest;
use Trackit\Http\Requests\UpdateTagRequest;
use Trackit\Contracts\Taggable;
use Trackit\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Taggable $taggable)
    {
        $tags = $taggable->tags;

        return Response::json($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Taggable $taggable, CreateTagRequest $request)
    {
        $tags = $request->tags;

        foreach ($tags as $tag) {
            $newTag = Tag::firstOrCreate(['name' => $tag]);
            $taggable->tags()->attach($newTag->id);
        }

        return Response::json($taggable->tags);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return Response::json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Tag $tag, UpdateTagRequest $request)
    {
        $data = $request->all();

        $tag->update($data);

        return Response::json($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response('', 204);
    }
}
