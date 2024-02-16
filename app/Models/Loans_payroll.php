<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans_payroll extends Model
{
    use HasFactory;
	public $table="loans_payrolls";

	public function loan()
    {
        return $this->belongsTo('App\Models\Loan','loan_id','id');
    }

    public function payroll()
    {
        return $this->belongsTo('App\Models\Payroll','payroll_id','id');
    }

}
