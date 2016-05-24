<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;
use Trackit\Models\Proposal;
use Trackit\Models\Project;

class DownloadAttachmentRequest extends Request
{
    /**
     * @var
     */
    protected $user;

    /**
     *
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $attachment = $this->route('attachment');

        // TODO: This is an ugly fix!
        $model = '';
        if (is_a($attachment->attachmentable, Proposal::class)) {
            $model = 'proposal';
        } else if (is_a($attachment->attachmentable, Project::class)) {
            $model = 'project';
        }

        return $attachment->attachmentable->allowsActionFrom($model.':attachment:download', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
