<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;

class UpdateProposalRequest extends Request
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
            'title' => 'required|max:100',
            'description' => 'required|max:5000',
            'tags' => 'array|max:20',
        ];
    }
}
