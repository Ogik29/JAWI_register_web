<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    protected $fillable = ['name'];

    /**
     * Relasi ke User: Satu role dimiliki banyak user
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
        // foreign_key: role_id di tabel users
        // local_key: id di tabel roles
    }
}
