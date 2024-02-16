<?php

namespace App\Http\Controllers\admin;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Branch,Department,Position};

class BranchController extends Controller
{
	public function Branches(Request $Request)
    {
        if ($this->CheckPermission('branch') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newBranch = new Branch();
                $newBranch -> code = $Request->input('code');
                $newBranch -> name = $Request->input('name');
                $newBranch -> save();

                $Departments = Department::where('branch_id', 2)->get();
                foreach ($Departments as $Department)
                {
                    $newDepartment = new Department();
                    $newDepartment -> code = $Department->code;
                    $newDepartment -> name = $Department->name;
                    $newDepartment -> branch_id = $newBranch->id;
                    $newDepartment ->save();

                    foreach ($Department->positions as $Position)
                    {
                        $newPosition = new Position();
                        $newPosition -> code = $Position->code;
                        $newPosition -> name = $Position->name;
                        $newPosition -> department_id = $newDepartment->id;
                        $newPosition ->save();
                    }
                }

                $this->AddToLog('Added Branch',$newBranch->name , $newBranch->id);
                $Request->session()->put('msgSuccess', 'The Branch has been added successfully');

            }
            else
            {
                $branches = Branch::all();
                $arr = array('branches' => $branches);
                return view('admin/branch_department/Branch',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }
}
