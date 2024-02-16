<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
	public $table="permissions";

    public function role_permissions()
    {
        return $this->hasMany('App\Models\Role_permission','permission_id','id');
    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->using('App\Models\Role_permission');
    }
}
