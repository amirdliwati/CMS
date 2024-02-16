<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
	public $table="payrolls";

	public function salary()
    {
        return $this->belongsTo('App\Models\Salarie','salary_id','id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function employee_issued()
    {
        return $this->belongsTo('App\Models\Employee','issued_employee_id','id');
    }

    public function pdf_template()
    {
        return $this->belongsTo('App\Models\Pdf_template','pdf_template_id','id');
    }

    public function deductions()
    {
        return $this->hasMany('App\Models\Deduction','payroll_id','id');
    }

    public function increments()
    {
        return $this->hasMany('App\Models\Increment','payroll_id','id');
    }

    public function loans_payrolls()
    {
        return $this->hasMany('App\Models\Loans_payroll','payroll_id','id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task','payroll_id','id');
    }
}
