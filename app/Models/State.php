<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
 	public $table="states";

    public function addresses()
    {
        return $this->hasMany('App\Models\Addresse','state_id','id');
    }
    public function countrie()
    {
        return $this->belongsTo('App\Models\Countrie','country_id','id');
    }
}
