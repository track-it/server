<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\Comment;

class CreateCommentRequest extends Request
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
        $commentable = Route::current()->parameters()[$keys[0]];

        return $commentable->allowsActionFrom($keys[0].':comment', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|max:'.config('filesystems.commentBodySize'),
        ];
    }
}
