<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;

class UpdateCommentRequest extends Request
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
        return $this->user->id == $this->route('comment')->author_id;
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