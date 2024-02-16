<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
	public $table="employees";

///////////////////// Basic ///////////////////////////
    public function users()
    {
        return $this->hasMany('App\Models\User','employee_id','id');
    }

	public function position()
    {
        return $this->belongsTo('App\Models\Position','position_id','id');
    }

    public function countrie()
    {
        return $this->belongsTo('App\Models\Countrie','country_id','id');
    }

    public function job_type()
    {
        return $this->belongsTo('App\Models\Job_type','job_type_id','id');
    }

    public function job_status()
    {
        return $this->belongsTo('App\Models\Job_status','status_id','id');
    }

    public function emergencies()
    {
        return $this->hasMany('App\Models\Emergencie','employee_id','id');
    }

    public function educations()
    {
        return $this->hasMany('App\Models\Education','employee_id','id');
    }

    public function legal_docs()
    {
        return $this->hasMany('App\Models\Legal_doc','employee_id','id');
    }

    public function emp_docs()
    {
        return $this->hasMany('App\Models\Emp_doc','employee_id','id');
    }

    public function experiences()
    {
        return $this->hasMany('App\Models\Experience','employee_id','id');
    }

    public function trainings()
    {
        return $this->hasMany('App\Models\Training','employee_id','id');
    }

    public function phones()
    {
        return $this->hasMany('App\Models\Phone','employee_id','id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Addresse','employee_id','id');
    }

    public function docs()
    {
        return $this->hasMany('App\Models\Doc','employee_id','id');
    }

    public function line1()
    {
        return $this->belongsTo('App\Models\Employee','line','id');
    }

    public function lines()
    {
        return $this->hasMany('App\Models\Employee','line','id');
    }

///////////////////// financial //////////////////////////////////////////

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance','employee_id','id');
    }

    public function deductions()
    {
        return $this->hasMany('App\Models\Deduction','employee_id','id');
    }

    public function payrolls()
    {
        return $this->hasMany('App\Models\Payroll','employee_id','id');
    }

    public function payroll_issued()
    {
        return $this->hasMany('App\Models\Payroll','issued_employee_id','id');
    }

    public function increments()
    {
        return $this->hasMany('App\Models\Increment','employee_id','id');
    }

    public function loans()
    {
        return $this->hasMany('App\Models\Loan','employee_id','id');
    }

    public function salaries()
    {
        return $this->hasMany('App\Models\Salarie','employee_id','id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task','employee_id','id');
    }

    public function leaves()
    {
        return $this->hasMany('App\Models\Leave','employee_id','id');
    }

    public function leave_roles()
    {
        return $this->hasMany('App\Models\Leave_role','employee_id','id');
    }

    public function leaves_by()
    {
        return $this->hasMany('App\Models\Leave','approved_by','id');
    }

    public function currencie()
    {
        return $this->belongsTo('App\Models\Currencie','currencies_id','id');
    }

    public function receipts_books()
    {
        return $this->hasMany('App\Models\Receipts_book','employee_id','id');
    }

    public function receipts_books_archive()
    {
        return $this->hasMany('App\Models\Receipts_book','employee_archive_id','id');
    }

    public function receipts_books_cancel()
    {
        return $this->hasMany('App\Models\Receipts_book','employee_cancel_id','id');
    }

/////////////////////  Others  ////////////////////////////////

    public function emails()
    {
        return $this->hasMany('App\Models\Email','employee_id','id');
    }

}
