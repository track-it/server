<?php

namespace Trackit\Http\Requests;

use Route;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;
use Trackit\Contracts\Attachmentable;

class CreateAttachmentRequest extends Request
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
        // TODO: Is there a more elegant solution for this?
        $keys = array_keys(Route::current()->parameters());
        $attachmentable = Route::current()->parameters()[$keys[0]];

        return $attachmentable->allowsActionFrom($keys[0].':edit', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // var $actualSize = $this->files->get('file')->getSize();
        return [
            // 'file' => 'required|max:'.config('filesystems.attachmentSizeInBytes'),
            // '*.size' => 'required|between:0,30',
            //'file.size' => 'max:0',
            //'file.size' => 'required',
        ];
    }
}
