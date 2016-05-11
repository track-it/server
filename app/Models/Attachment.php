<?php

namespace Trackit\Models;

use Trackit\Contracts\RestrictsAccess;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model implements RestrictsAccess
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'path',
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
        'mime_type',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'url'
    ];

    /**
     * Add an attribute mutator to the model to get its
     * url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return env('APP_URL').'/attachments/'.$this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function allowsActionFrom($action, $user)
    {
        // Allow if user is uploader of attachment
        if ($this->author_id == $user->id) {
            return true;
        }

        // Allow if user has permission to do action on the attachment's parent resource
        if ($this->attachmentable()->allowsActionFrom($action, $user)) {
            return true;
        }

        return false;
    }

    /**
     * Get the relationship between the attachment and its uploader.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /**
     * Get the relationship between the attachment and its
     * attachmentable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachmentable()
    {
        return $this->morphTo();
    }
}
