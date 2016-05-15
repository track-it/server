<?php

namespace Trackit\Models;

use Trackit\Contracts\Taggable;
use Trackit\Models\ProjectRole;
use Trackit\Models\ProjectUser;
use Trackit\Contracts\Searchable;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Attachmentable;
use Trackit\Contracts\RestrictsAccess;
use Illuminate\Database\Eloquent\Model;

class Project extends Model implements Attachmentable, Commentable, Searchable, Taggable, RestrictsAccess
{
    /**
     * Status indicating that the project is
     * not completed.
     *
     * @var int
     */
    const NOT_COMPLETED = 1;

    /**
     * Status indicating that the project is
     * completed.
     *
     * @var int
     */
    const COMPLETED = 2;

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

    public static function search($string, $statuses)
    {
        return self::where('title', 'like', "%$string%")
                    ->orWhereHas('tags', function ($query) use ($string) {
                        $query->where('name', 'LIKE', "%$string%");
                    })->get()->filter(function ($proposal) use ($statuses) {
                        return in_array($proposal->status, $statuses);
                    });
    }

    /**
     * {@inheritdoc}
     */
    public function allowsActionFrom($action, $user)
    {
        // Get project statuses for this action. These are limiting.
        $projectStatuses = $user->role->accessTo($action);

        // Allow if user is part of project and has project permission
        $participant = $this->participants()->find($user->id);
        if ($participant && $participant->pivot->projectRole->can($action)) {
            // If action is constrained by statuses, check them
            if (sizeof($projectStatuses) > 0 && !in_array($this->status, $projectStatuses)) {
                return false;
            }

            return true;
        }

        // We need to do this so that default if no status defined is all statuses
        $globalStatuses = $user->role->accessTo('global:'.$action);
        $globalStatuses = sizeof($globalStatuses) > 0 ? $globalStatuses : Project::STATUSES;
        // Allow if user has global permission
        if ($user->can($action) && in_array($this->status, $globalStatuses)) {
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
    public function addParticipant($role, $user)
    {
        $this->participants()->attach([
            $user->id => [
                'project_role_id' => ProjectRole::byName($role)->first()->id,
            ],
        ]);
    }

    /**
     * Returns an array of users that should be notified
     */
    public function getNotificationRecipients(User $user)
    {
        return $this->participants
            ->filter(function ($participant) use ($user) {
                return $participant->id != $user->id;
            })
            ->map(function ($participant) {
                return $participant->email;
            })
            ->toArray();
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
     * Get the relationship between the projects and its participating users
     */
    public function participants()
    {
        return $this->belongsToMany(User::class)->withPivot('project_role_id');
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

    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        if ($parent instanceof User) {
            return new ProjectUser($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
