<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;

class UpdateTagRequest extends Request
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
        $tag = $this->route('tag');

        return $tag->allowsActionFrom('tag:edit', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:20|regex:/^[a-zA-Z]{1}\w+$/'
        ];
    }
}
