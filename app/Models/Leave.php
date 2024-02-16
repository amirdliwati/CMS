<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
	public $table="leaves";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

	public function manager()
    {
        return $this->belongsTo('App\Models\Employee','approved_by','id');
    }

	public function leave_type()
    {
        return $this->belongsTo('App\Models\Leave_type','type_id','id');
    }

}
