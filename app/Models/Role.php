<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function can($action)
    {
        return !! $this->permissions()
                       ->where('name', $action)
                       ->first();
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function givePermissionTo()
    {
        //DO THE TEST FIRST!
        
    }
}
