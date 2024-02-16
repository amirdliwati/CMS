<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresse extends Model
{
    use HasFactory;
 	public $table="addresses";

 	public function countrie()
    {
        return $this->belongsTo('App\Models\Countrie','country_id','id');
    }
    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id','id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

}
