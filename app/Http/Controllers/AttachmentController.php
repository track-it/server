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
use Trackit\Http\Requests\DeleteRequest;
use Trackit\Http\Requests\MassDeleteAttachmentRequest;
use Trackit\Http\Requests\DownloadAttachmentRequest;

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
        $files = array_key_exists('files', $request->allFiles()) ? $request->allFiles()['files'] : [];

        foreach ($files as $file) {
            $path = 'attachments/'.
                    strtolower(class_basename(get_class($attachmentable))).'_'.$attachmentable->getId().'/'.
                    $file->getClientOriginalName();

            $data = [
                'title' => $file->getClientOriginalName(),
                'uploader_id' => $this->user->id,
                'attachmentable_id' => $attachmentable->getId(),
                'attachmentable_type' => get_class($attachmentable),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
            ];

            $attachment = Attachment::create($data);

            Storage::put($path, file_get_contents($file));
        }

        return Response::json($attachmentable->attachments);
    }

    /**
     * Download a file attached to an attachment.
     *
     * @param  \Trackit\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function download(Attachment $attachment, DownloadAttachmentRequest $request)
    {
        return Response::file(storage_path('app/'.$attachment->path));
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
    public function destroy(Attachment $attachment, DeleteRequest $request)
    {
        $attachment->delete();

        return response('', 204);
    }

    /**
     * Removes all specified resources from storage.
     */
    public function massDestroy(MassDeleteAttachmentRequest $request)
    {
        foreach ($request->attachment_ids as $id) {
            $attachment = Attachment::find($id);
            if ($attachment) {
                Storage::delete($attachment->path);
                $attachment->delete();
            }
        }

        return response('', 204);
    }
}
