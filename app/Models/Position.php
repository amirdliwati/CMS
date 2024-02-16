<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
	public $table="positions";

	public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id','id');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employee','position_id','id');
    }
}
