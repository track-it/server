<?php

namespace Trackit\Http\Controllers;

use Illuminate\Http\Request;

use Trackit\Http\Requests;
use Trackit\Models\Proposal;
use Trackit\Models\Attachment;
use Trackit\Http\Requests\CreateAttachmentRequest;

class ProposalAttachmentController extends AttachmentController
{
    public function show(Proposal $proposal, Attachment $attachments)
    {
        return parent::showAttachment($proposal, $attachments);
    }

    public function store(Proposal $proposal, CreateAttachmentRequest $request)
    {
        return parent::storeAttachment($proposal, $request);
    }
}
