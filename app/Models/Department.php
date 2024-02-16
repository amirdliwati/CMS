<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
	public $table="departments";

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch','branch_id','id');
    }

    public function positions()
    {
        return $this->hasMany('App\Models\Position','department_id','id');
    }

}
