<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Hash;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = [
        'name',
    ];

    public static function boot()
    {
    	parent::boot();

    	static::created(function ($user) {
    		$user->refreshApiToken();
    	});

        static::creating(function($user) {
            $user->password = Hash::make($user->password);
        });
    }

    public static function scopeByUsername($query, $username)
    {
        return $query->where('username', $username);
    }

    public function refreshApiToken()
    {
    	$this->api_token = str_random(128);
    	$this->save();
    }

    public function proposals()
    {
    	return $this->hasMany(Proposal::class);
    }

    public function role()
    {
    	return $this->belongsTo(Role::class);
    }
}
