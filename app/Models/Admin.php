<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * A person who can log in to the admin panel (table: admins).
 * Website visitors never log in, so this is the only login in the project.
 */
class Admin extends Authenticatable
{
    protected $table = 'admins';

    protected $fillable = ['name', 'email', 'password', 'last_login_at'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];
}
