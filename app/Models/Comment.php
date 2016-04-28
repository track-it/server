<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
    ];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function commentable()
	{
		$this->morphTo(Project::class);
	}
}
