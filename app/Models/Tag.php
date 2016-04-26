<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
    	'name',
    ];

    public function proposals()
    {
    	return $this->morphedByMany(Proposal::class, 'taggable');
    }
}
