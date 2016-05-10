<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;
use Trackit\Models\User;

class UpdateProposalRequest extends Request
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
        $proposal = $this->route('proposal');

        return $proposal->allowsActionFrom('proposal:edit', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => config('validation.title'),
            'description'   => config('validation.description') . '|required',
            'tags'          => config('validation.tags'),
            'tags.*'        => config('validation.tag'),
        ];
    }
}
