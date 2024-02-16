<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_status extends Model
{
    use HasFactory;
	public $table="jobs_status";

	public function employees()
    {
        return $this->hasMany('App\Models\Employee','status_id','id');
    }
}
