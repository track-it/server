<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;

use Trackit\Http\Requests;
use Trackit\Models\Attachment;
use Trackit\Models\User;
use Trackit\Support\JsonResponse;
use Trackit\Http\Requests\CreateAttachmentRequest;
use Trackit\Contracts\Attachmentable;

class AttachmentController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Attachmentable $attachmentable, CreateAttachmentRequest $request)
    {
        $attachments = [];

        foreach ($request->allFiles() as $file) {
            $data = [
                'title' => $file->getClientOriginalName(),
                'uploader_id' => $this->user->id ? $this->user->id : 0,
                'source_id' => $attachmentable->getId(),
                'source_type' => get_class($attachmentable),
            ];

            $attachments[] = Attachment::create($data);
        }

        return JsonResponse::success($attachments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attachmentable $attachmentable, Attachment $attachment)
    {
        return JsonResponse::success($attachment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
