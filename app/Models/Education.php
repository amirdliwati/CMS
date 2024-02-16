<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
	public $table="educations";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function countrie()
    {
        return $this->belongsTo('App\Models\Countrie','location','id');
    }

}
