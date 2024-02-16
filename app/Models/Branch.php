<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
	public $table="branchs";

	public function departments()
    {
        return $this->hasMany('App\Models\Department','branch_id','id');
    }

}
