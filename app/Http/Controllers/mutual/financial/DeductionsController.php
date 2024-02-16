<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth;
use App\Models\{Deduction,Employee};

class DeductionsController extends Controller
{
    public function Deductions()
    {
        $Employee = Employee::find(Auth::user()->employee->id);
        $Deductions = Deduction::where('employee_id',Auth::user()->employee->id)->get();
        $arr = array('Deductions' => $Deductions, 'Employee' => $Employee);
        return view('mutual/financial/Deductions',$arr);
    }

}
