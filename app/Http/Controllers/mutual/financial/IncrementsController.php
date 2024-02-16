<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth;
use App\Models\{Increment,Employee};

class IncrementsController extends Controller
{
    public function Increments()
    {
        $Employee = Employee::find(Auth::user()->employee->id);
        $Increments = Increment::where('employee_id',Auth::user()->employee->id)->get();
        $arr = array('Increments' => $Increments, 'Employee' => $Employee);
        return view('mutual/financial/Increments',$arr);
    }

}
