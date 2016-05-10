<?php

namespace Trackit\Http\Requests;

use Route;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;

class CreateTagRequest extends Request
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
        $taggable = Route::current()->parameters()[$keys[0]];

        return $taggable->allowsActionFrom($keys[0].':edit', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tags'      => config('validation.tags') . '|required',
            'tags.*'    => config('validation.tag'),
        ];
    }
}
