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

        // If status is the only field in request, check if user has permission
        // to update it.
        if (sizeof($this->all()) == 1 && $this->status) {
            return $proposal->allowsActionFrom('proposal:edit:status', $this->user);
        }

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
            'description'   => config('validation.description'),
            'tags'          => config('validation.tags'),
            'tags.*.name'   => config('validation.tag'),
        ];
    }
}
