<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
	public $table="loans";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function salary()
    {
        return $this->belongsTo('App\Models\Salarie','salary_id','id');
    }

    public function payrolls()
    {
        return $this->hasMany('App\Models\Payroll','loan_id','id');
    }

    public function loans_payrolls()
    {
        return $this->hasMany('App\Models\Loans_payroll','loan_id','id');
    }
}
