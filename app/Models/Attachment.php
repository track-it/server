<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

use Trackit\Models\User;
use Trackit\Contracts\RestrictsAccess;

class Attachment extends Model implements RestrictsAccess
{
    protected $fillable = [
        'title',
        'url',
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
    ];

    /**
     *
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

    /*
     *
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /*
     *
     */
    public function attachmentable()
    {
        return $this->morphTo();
    }
}
