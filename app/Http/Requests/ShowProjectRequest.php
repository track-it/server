<?php

namespace Trackit\Http\Requests;

use Trackit\Http\Requests\Request;

class ShowProjectRequest extends Request
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
        $project = $this->route('project');

        // If a project is completed, it's visible to everyone regardless of permissions
        if ($project->status == Project::COMPLETED) {
            return true;
        }

        return $project->allowsActionFrom('project:view', $user);
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
