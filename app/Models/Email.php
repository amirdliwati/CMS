<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
	public $table="emails";

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

}
