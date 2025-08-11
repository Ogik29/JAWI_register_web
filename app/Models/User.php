<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username', 'password', 'role_id', 'name'
    ];

    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function contingent()
    {
        return $this->hasOne(Contingent::class, 'user_id', 'id');
    }

    public function eventRoles()
    {
        return $this->hasMany(EventRole::class, 'user_id', 'id');
    }
}
