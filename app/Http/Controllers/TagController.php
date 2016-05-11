<?php

namespace Trackit\Http\Controllers;

use Response;
use Trackit\Models\Tag;
use Illuminate\Http\Request;
use Trackit\Contracts\Taggable;
use Trackit\Http\Requests\CreateTagRequest;
use Trackit\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Return a JSON response for all tags of the given
     * taggable model.
     *
     * @param  \Trackit\Contracts\Taggable  $taggable
     * @return \Illuminate\Http\Response
     */
    public function index(Taggable $taggable)
    {
        $tags = $taggable->tags;

        return Response::json($tags);
    }

    /**
     * Create a new tag for the given taggable model.
     *
     * @param  \Trackit\Contracts\Taggable  $taggable
     * @param  \Trackit\Http\Requests\CreateTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Taggable $taggable, CreateTagRequest $request)
    {
        $tags = $request->tags == null ? [] : $request->tags;

        foreach ($tags as $tag) {
            $newTag = Tag::firstOrCreate(['name' => $tag]);
            $taggable->tags()->attach($newTag->id);
        }

        return Response::json($taggable->tags);
    }

    /**
     * Return a JSON response for the given tag.
     *
     * @param  \Trackit\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return Response::json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Trackit\Models\Tag  $tag
     * @param  \Trackit\Http\Requests\UpdateTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Tag $tag, UpdateTagRequest $request)
    {
        $tag->update($request->all());

        return Response::json($tag);
    }

    /**
     * Remove the given tag.
     *
     * @param  \Trackit\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response('', 204);
    }
}
