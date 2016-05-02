<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Models\User;

class Attachment extends Model
{
    protected $fillable = [
        'title',
        'url',
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
    ];

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
