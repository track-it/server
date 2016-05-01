<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author_id',
        'body',
        'source_id',
        'source_type',
    ];

	public function author()
	{
		return $this->belongsTo(User::class, 'author_id');
	}

	public function source()
	{
		return $this->morphTo();
	}

	public function commentable()
	{
		$this->morphTo(Project::class);
	}
}
