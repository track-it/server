<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;

class CreateTagRequest extends Request
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
        return [
            'tags' => 'required|array|max:20',
            'tags.*' => 'required|string|max:20|regex:/^[a-zA-Z]{1}\w+$/'
        ];
    }
}
