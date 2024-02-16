<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salarie extends Model
{
    use HasFactory;
	public $table="salaries";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function payrolls()
    {
        return $this->hasMany('App\Models\Payroll','salary_id','id');
    }

    public function deductions()
    {
        return $this->hasMany('App\Models\Deduction','salary_id','id');
    }

    public function increments()
    {
        return $this->hasMany('App\Models\Increment','salary_id','id');
    }

    public function loans()
    {
        return $this->hasMany('App\Models\Loan','salary_id','id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task','salary_id','id');
    }
}
