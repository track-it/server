<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the relationship between the tag and all of its related
     * proposals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function proposals()
    {
        return $this->morphedByMany(Proposal::class, 'taggable');
    }
}
