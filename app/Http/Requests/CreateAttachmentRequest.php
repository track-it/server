<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;

class CreateAttachmentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'file.size' => 'max:0',
            //'file.size' => 'required',
        ];
    }
}
