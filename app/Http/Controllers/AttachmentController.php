<?php

namespace Trackit\Http\Controllers;

use Auth;
use Storage;
use Response;
use Trackit\Models\User;
use Trackit\Http\Requests;
use Illuminate\Http\Request;
use Trackit\Models\Attachment;
use Trackit\Contracts\Attachmentable;
use Trackit\Http\Requests\CreateAttachmentRequest;
use Trackit\Http\Requests\UpdateAttachmentRequest;

class AttachmentController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * List all attachments for an attachmentable model.
     *
     * @param  \Trackit\Contracts\Attachmentable  $attachmentable
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
     * @param  \Trackit\Contracts\Attachmentable  $attachmentable
     * @param  \Trackit\Http\Requests\CreateAttachmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Attachmentable $attachmentable, CreateAttachmentRequest $request)
    {
        $files = $request->allFiles()['files'];
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
     * @param  \Trackit\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return Response::json($attachment);
    }

    /**
     * Download a file attached to an attachment.
     *
     * @param  \Trackit\Models\Attachment  $attachment
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
     * @param  \Trackit\Models\Attachment  $attachment
     * @param  \Trackit\Http\Requests\UpdateAttachmentRequest  $request
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
     * @param  \Trackit\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return response('', 204);
    }
}
