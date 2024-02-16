<?php

namespace App\Http\Controllers\hr;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{User,Department,Position,Employee,Role};

class PositionController extends Controller
{
	public function Positions(Request $Request)
    {
        if ($this->CheckPermission('position') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newposition = new Position();
                $newposition -> name = $Request->input('name');
                $newposition -> code = $Request->input('code');
                $newposition -> department_id = $Request->input('department_id');
                $newposition -> save();

                $this->AddToLog('Added Position',$newposition->name , $newposition->id);
                $Request->session()->put('msgSuccess', 'The Position has been added successfully');
            }
            else
            {
                $positions = Position::all();
                $departments = Department::all();
                $arr = array('positions' => $positions, 'departments' => $departments);
                return view('hr/Position',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function RoleEmployee(Request $Request)
    {
        if ($this->CheckPermission('RoleEmployee') == true)
        {
            if ($Request->isMethod('post'))
            {
                $Role = Role::find($Request->input('department'));
                $User = User::find($Request->input('employee'));

                if ($User->roles->where('title',$Role->title) != '[]') {
                    return response()->json('error role');
                }

                $newRole = new Role();
                $newRole -> title = $Role->title;
                $newRole -> active = 1;
                $newRole -> blug = $Role->blug;
                $newRole -> content = 'Custom Role for '.$Role->blug;
                $newRole -> user_id = $Request->input('employee');
                $newRole -> save();

                return response()->json('ok');
            }
            else
            {
                $Employees = User::join('employees','employees.id','=','users.employee_id')
                ->join('roles','roles.user_id','=','users.id')
                ->whereIn('employees.status_id',[1,2])->where('roles.title','<>','Admin_role')->select('users.*')->distinct('users.id')->get();

                $arr = array('Employees' => $Employees);
                return view('hr/RoleEmployee',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function RolesList(Request $Request)
    {
        $RolesUser = Role::join('users','users.id','=','roles.user_id')->where('roles.user_id',$Request->UserID)->select('roles.title')->get();
        $Roles = Role::whereNotIn('title',$RolesUser)->whereNull('user_id')->where('title','<>','Admin_role')->pluck('blug','id');
        return response()->json($Roles);
    }
}
