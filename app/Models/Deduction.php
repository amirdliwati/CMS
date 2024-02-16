<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;
	public $table="deductions";

	public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function salary()
    {
        return $this->belongsTo('App\Models\Salarie','salary_id','id');
    }

    public function payroll()
    {
        return $this->belongsTo('App\Models\Payroll','payroll_id','id');
    }
}
