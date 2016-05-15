<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;
use Trackit\Models\Attachment;

class MassDeleteAttachmentRequest extends Request
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
        if ($this->attachment_ids) {
            foreach ($this->attachment_ids as $id) {
                $attachment = Attachment::find($id);
                if ($attachment && !$attachment->attachmentable->allowsActionFrom('attachment:delete', $this->user)) {
                    return false;
                }
            }
            return true;
        }

        return false;
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
