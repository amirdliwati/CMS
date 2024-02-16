<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legal_doc extends Model
{
    use HasFactory;
	public $table="legal_docs";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }
}
