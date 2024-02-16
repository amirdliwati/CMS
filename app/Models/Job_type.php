<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_type extends Model
{
    use HasFactory;
	public $table="job_types";

	public function employees()
    {
        return $this->hasMany('App\Models\Employee','job_type_id','id');
    }
}
