<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_doc extends Model
{
    use HasFactory;
	public $table="emp_docs";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

}
