<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countrie extends Model
{
    use HasFactory;
 	public $table="countries";

 	public function states()
    {
        return $this->hasMany('App\Models\State','country_id','id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Addresse','country_id','id');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employee','country_id','id');
    }

    public function educations()
    {
        return $this->hasMany('App\Models\Education','location','id');
    }

    public function experiences()
    {
        return $this->hasMany('App\Models\Experience','job_loc','id');
    }

    public function trainings()
    {
        return $this->hasMany('App\Models\Training','course_loc','id');
    }

    public function item_details()
    {
        return $this->hasMany('App\Models\Item_detail','origin_country','id');
    }

}
