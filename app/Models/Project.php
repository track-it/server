<?php

namespace Trackit\Models;

use Trackit\Contracts\Taggable;
use Trackit\Models\ProjectRole;
use Trackit\Models\ProjectUser;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Attachmentable;
use Trackit\Contracts\RestrictsAccess;
use Illuminate\Database\Eloquent\Model;

class Project extends Model implements Attachmentable, Commentable, Taggable, RestrictsAccess
{
    /**
     * Status indicating that the project is
     * completed.
     *
     * @var int
     */
    const COMPLETED = 1;

    /**
     * Status indicating that the project is
     * not completed.
     *
     * @var int
     */
    const NOT_COMPLETED = 2;

    /**
     * Status indicating that the project is
     * published.
     *
     * @var int
     */
    const PUBLISHED = 3;

    /**
     * @var array
     */
    const STATUSES = [
        self::NOT_COMPLETED,
        self::COMPLETED,
        self::PUBLISHED,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'team_id',
        'proposal_id',
    ];

    /**
     * Get the project’s id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function allowsActionFrom($action, $user)
    {
        // Allow if user is part of project and has project permission
        $projectUser = $this->projectUsers()->where(['user_id' => $user->id])->first();
        if ($projectUser && $projectUser->can($action)) {
            return true;
        }

        // Allow if user has global permission
        $statuses = $user->role->accessTo($action);
        $statuses = sizeof($statuses) > 0 ? $statuses : Project::STATUSES;
        if ($user->can($action) && in_array($this->status, $statuses)) {
            return true;
        }

        return false;
    }

    /**
     * Adds a user to the project.
     *
     * @param  string  $role
     * @param  \Trackit\Models\User  $user
     * @return \Trackit\Models\ProjectUser
     */
    public function addProjectUser($role, $user)
    {
        $projectUser = ProjectUser::create([
            'user_id' => $user->id,
            'project_role_id' => ProjectRole::byName($role)->first()->id,
            'project_id' => $this->id,
        ]);

        return $projectUser;
    }

    /**
     * Get the relationship between the project and its
     * attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    /**
     * Get the relationship betwen the project and its
     * proposal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the relationship between the project and its team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the relationship between the project and its comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the relationship between the project and its workflow.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function workflow()
    {
        return $this->hasOne(Workflow::class);
    }

    /**
     * Get the relationship between the project and its
     * ProjectUser models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class);
    }

    /**
     * Get the relationship between the project and its tags.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
