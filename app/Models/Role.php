<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public $table="roles";

    public function role_permissions()
    {
        return $this->hasMany('App\Models\Role_permission','role_id','id');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User','user_id','id');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_permissions', 'role_id', 'permission_id');
    }
}
