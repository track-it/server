<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Response;
use Auth;

use Trackit\Http\Requests;
use Trackit\Models\Attachment;
use Trackit\Models\User;
use Trackit\Http\Requests\CreateAttachmentRequest;
use Trackit\Http\Requests\UpdateAttachmentRequest;
use Trackit\Contracts\Attachmentable;

class AttachmentController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * List all attachments for an attachmentable
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Attachmentable $attachmentable)
    {
        $attachments = $attachmentable->attachments;

        return Response::json($attachments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Attachmentable $attachmentable, CreateAttachmentRequest $request)
    {
        $files = array_key_exists('files', $request->allFiles()) ? $request->allFiles()['files'] : [];
        foreach ($files as $file) {
            $data = [
                'title' => $file->getClientOriginalName(),
                'uploader_id' => $this->user->id,
                'attachmentable_id' => $attachmentable->getId(),
                'attachmentable_type' => get_class($attachmentable),
                'mime_type' => $file->getMimeType(),
            ];

            $attachment = Attachment::create($data);
            
            Storage::put('attachments/'.$attachment->id.'/'.$file->getClientOriginalName(), file_get_contents($file));
        }
        return Response::json($attachmentable->attachments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return Response::json($attachment);
    }

    /**
     * Download a file attached to an attachment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Attachment $attachment)
    {
        $content = Storage::get($attachment->url);
        $headers = [
          'Content-Type' => $attachment->mime_type,
        ];
        return response($content, 200, $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Attachment $attachment, UpdateAttachmentRequest $request)
    {
        $attachment->update($request->all());

        return Response::json($attachment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return response('', 204);
    }
}
