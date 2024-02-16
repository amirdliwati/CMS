<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth;
use App\Models\{Loan,Employee};

class LoansController extends Controller
{
    public function Loans()
    {
        $Employee = Employee::find(Auth::user()->employee->id);
        $Loans = Loan::where('employee_id',Auth::user()->employee->id)->get();
        $arr = array('Loans' => $Loans, 'Employee' => $Employee);
        return view('mutual/financial/Loans',$arr);
    }

}
