<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    /**
     * @var
     */
    protected $fillable = [
        'role_id',
        'permission',
        'status',
    ];
}
