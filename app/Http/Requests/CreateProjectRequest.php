<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;

class CreateProjectRequest extends Request
{
    protected $user;

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
        return $this->user->can('project:create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => config('validation.title') . '|required',
            'description'   => config('validation.description'),
            'tags'          => config('validation.tags'),
            'tags.*.name'   => config('validation.tag'),
        ];
    }
}
