<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'url',
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
    ];

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
