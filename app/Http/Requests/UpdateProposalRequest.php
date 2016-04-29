<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;

class UpdateProposalRequest extends Request
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
        return $this->user->id == $this->route('proposal')->user_id;
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
