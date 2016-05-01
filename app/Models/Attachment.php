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
        'source_id',
        'source_type',
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
    public function source()
    {
        return $this->morphTo();
    }
}
