<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
	public $table="phones";

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

}
