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

	public function removeComment($comment_id, $user_id)
	{
		$this->hasMany(Comment::class)->whereCommentId($comment_id)->whereUserId($user_id)->delete();
	}
}
