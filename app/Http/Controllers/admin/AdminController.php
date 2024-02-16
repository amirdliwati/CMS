<?php

namespace App\Http\Controllers\admin;

use Controller, Auth, Hash;
use Illuminate\Http\Request;
use App\Models\{User,Role,Role_permission,Employee,Log};

class AdminController extends Controller
{
	public function ManageUsersFunction()
    {
        if ($this->CheckPermission('ManageUsers') == true)
        {
            $Users = User::where([['id', '>' ,1],['email','!=','null@naya-est.com']])->get();
            $arr = array('Users' => $Users);
            return view('admin/UsersManager',$arr);
        }
        else
        {
            return view('errors/404');
        }
    }

    public function StatusUser(Request $Request)
    {
        if ($this->CheckPermission('ManageUsers') == true)
        {
            $StatusUser = User::find($Request->id_user);

            if ($StatusUser->status == 1)
            {
                $StatusUser -> status = 2;
                $StatusUser -> save();
                $this->AddToLog('User Deactivated', $StatusUser->id , $StatusUser->name);
                return response()->json("User Deactivated");
            }
            else
            {
                $StatusUser -> status = 1;
                $StatusUser -> save();
                $this->AddToLog('User Activated', $StatusUser->id , $StatusUser->name);
                return response()->json("ok");
            }
        }
        else
        {
            return view('errors/404');
        }
    }

    public function ChangeUserPass(Request $Request)
    {
        if ($this->CheckPermission('ManageUsers') == true)
        {
            $User = User::find($Request->input('user_id'));
            $User -> password =Hash::make($Request->input('password'));
            $User -> save();
            return response()->json("ok");
        }
        else
        {
            return response()->json("Access Denied");
        }
    }

    public function EditPermissions(Request $Request)
    {
        if ($this->CheckPermission('ManageUsers') == true)
        {
            $User = User::find($Request->UserID);
            $RolesUser = Role::join('users','users.id','=','roles.user_id')->where('roles.user_id',$Request->IDuser)->select('roles.title')->get();
            $Roles = Role::whereIn('title',$RolesUser)->whereNull('user_id')->where('title','<>','Admin_role')->get();
            $arr = array('User' => $User, 'Roles' => $Roles);
            return view('admin/UserPermissions',$arr);
        }
        else
        {
            return view('errors/404');
        }
    }

    public function changePermission(Request $Request)
    {
        if ($this->CheckPermission('ManageUsers') == true)
        {
            $exist = Role_permission::where([['role_id', $Request->RoleID],['permission_id', $Request->PermID]])->first();
            if (empty($exist))
            {
                Role_permission::insert(['role_id' => $Request->RoleID, 'permission_id' => $Request->PermID]);
                return response()->json('ok');
            }
            else
            {
                Role_permission::where([['role_id', $Request->RoleID],['permission_id', $Request->PermID]])->delete();
                return response()->json('deleted');
            }
        }
        else
        {
            return view('errors/404');
        }
    }

    public function UserChangePassword(Request $Request)
    {
        if ($Request->isMethod('post'))
        {
            User::find(Auth::user()->id)->update(['password'=> Hash::make($Request->input('newpassword'))]);
            $Request->session()->put('msgSuccess', 'Password changed successfully');
        }
        else
        {
            return view('admin/ChangePassword');
        }
    }

    public function UserActivityLog()
    {
        $logs = Log::latest()->limit(2000)->get();
        $arr = array('logs' => $logs);
        return view('admin/Logs', $arr);
    }

    public function UserProfile()
    {
        $Employee = Employee::find(Auth::user()->employee_id);
        $arr = array('Employee' => $Employee);
        return view('admin/UserProfile', $arr);
    }

    public function GetEmployeePermissions(Request $Request)
    {
        if ($this->CheckPermission('ManageUsers') == true)
        {
            $User = User::find($Request->UserID);
            $RolesUser = Role::join('users','users.id','=','roles.user_id')->where('roles.user_id',$Request->UserID)->select('roles.title')->get();
            $Roles = Role::whereIn('title',$RolesUser)->whereNull('user_id')->where('title','<>','Admin_role')->get();

            $response = array();
            foreach($Roles as $Role){
                foreach($Role->permissions as $per){
                    foreach($User->roles->where('title',$Role->title) as $UserRole){
                        $response[] = array("id"=>$per->id,
                        "content"=>$per->content,
                        "department"=>$Role->blug,
                        "permission"=>[
                            "permission_status"=>$UserRole->permissions->where('title', $per->title),
                            "permission_id"=>$per->id,
                            "user_id"=>$UserRole->id]);
                    }
                }
            }

            return response()->json($response);
        }
        else
        {
            return response()->json('Access Denied');
        }
    }
}
