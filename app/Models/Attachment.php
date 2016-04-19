<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Models\User;

class Attachment extends Model
{

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
