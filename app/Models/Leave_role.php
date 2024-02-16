<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave_role extends Model
{
    use HasFactory;
    public $table="leave_roles";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }
}
