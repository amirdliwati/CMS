<?php

namespace App\Http\Controllers\admin;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Branch,Department};

class DepartmentController extends Controller
{
    public function Departments(Request $Request)
    {
        if ($this->CheckPermission('department') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newDepartment = new Department();
                $newDepartment -> name = $Request->input('name');
                $newDepartment -> code = $Request->input('code');
                $newDepartment -> branch_id = $Request->input('branch_id');
                $newDepartment -> save();

                $this->AddToLog('Added Department',$newDepartment->name , $newDepartment->id);
                $Request->session()->put('msgSuccess', 'The Department has been added successfully');
            }
            else
            {
                $departments = Department::all();
                $branches = Branch::all();
                $arr = array('branches' => $branches, 'departments' => $departments);
                return view('admin/branch_department/Department',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

}
