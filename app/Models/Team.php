<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
	protected $fillable = [
    
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }
}
