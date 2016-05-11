<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * Get the relationship between the cours and its
     * proposals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function proposals()
	{
		return $this->hasMany(Project::class);
	}
}
