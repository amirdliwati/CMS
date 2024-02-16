<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave_type extends Model
{
    use HasFactory;
	public $table="leave_types";

    public function leaves()
    {
        return $this->hasMany('App\Models\Leave','type_id','id');
    }
}
