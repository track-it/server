<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Models\User;

class Attachment extends Model
{
    protected $fillable = [
        'title',
        'path',
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
    ];

    /**
     * @var
     */
    protected $hidden = [
        'uploader_id',
        'attachmentable_id',
        'attachmentable_type',
        'mime_type',
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

    /**
     *
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'url' => env('APP_URL').'/attachments/'.$this->id,
        ]);
    }
}
