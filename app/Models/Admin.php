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

    protected $fillable = ['name', 'email', 'password', 'role', 'last_login_at'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function isSuper()
    {
        return $this->role === config('admin.super_role');
    }

    /** Can this admin open the given section? (permissions come from config/admin.php > roles) */
    public function hasPermission($permission)
    {
        $allowed = config('admin.roles.' . $this->role . '.permissions', []);

        return in_array('*', $allowed, true) || in_array($permission, $allowed, true);
    }

    public function getRoleLabelAttribute()
    {
        return config('admin.roles.' . $this->role . '.label', $this->role);
    }
}
