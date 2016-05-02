<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	public function proposals()
	{
		return $this->hasMany(Project::class);
	}
}
