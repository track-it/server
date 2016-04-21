<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Models\User;

class Attachment extends Model
{
    protected $appends = array('url');

    protected $fillable = [
        'title',
        'url',
        'uploader_id',
        'source_id',
        'source_type',
    ];

    /**
     *
     */
    public function getUrlAttribute()
    {
        return route('attachments.download', ['attachment' => $this->id]);
    }

    /*
     *
     */
    public function uploader()
    {
        return $this->belongsTo(User::class);
    }

    /*
     *
     */
    public function source()
    {
        return $this->morphTo();
    }
}
