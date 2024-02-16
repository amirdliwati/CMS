<?php

namespace App\Http\Controllers\hr;

use Controller, Auth, Hash, Carbon, Storage, DB;
use Illuminate\Http\Request;
use App\Models\{User,Department,Position,Employee,Salarie,Role,Addresse,Phone,Emergencie,Job_type,Job_status,Countrie,State};

class EmployeeController extends Controller
{
///////////////////////////////////////////// Add Employee ////////////////////////////////////////////
    public function Employeefunction(Request $Request)
    {
        if ($this->CheckPermission('AddEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $Email = Employee::where('system_email',$Request->input('system_email'))->first();
                if (empty($Email)) {
                    $newEmp = new Employee();
                    $newEmp -> prefix = $Request->input('prefix');
                    $newEmp -> first_name = $Request->input('first_name');
                    $newEmp -> middle_name = $Request->input('middle_name');
                    $newEmp -> last_name = $Request->input('last_name');
                    $newEmp -> mother = $Request->input('mother');
                    $newEmp -> birthdate = $Request->input('birthdate');
                    $newEmp -> blood = $Request->input('blood');
                    $newEmp -> gender = $Request->input('gender');  //female=1, male=2
                    $newEmp -> marital_status = $Request->input('marital_status');  //sin=1,mar=2,div=3,wid=4
                    $newEmp -> country_id = $Request->input('Nationality');
                    $newEmp -> national_no = $Request->input('national_no');
                    $newEmp -> passport = $Request->input('passport');
                    $newEmp -> email = $Request->input('email');
                    $newEmp -> system_email = $Request->input('system_email');
                    $newEmp -> line = $Request->input('line');
                    $newEmp -> hire_date = $Request->input('hire_date');
                    $newEmp -> resignation_date = $Request->input('resignation_date');
                    $newEmp -> job_type_id = $Request->input('job_type_id');
                    $newEmp -> status_id = $Request->input('status_id');
                    $newEmp -> position_id = $Request->input('position');
                    $newEmp -> save();

                    if ($Request->hasFile('emp_image'))
                    {
                        $file = $Request->file('emp_image');
                        $filename ='ProfilePic_'. $newEmp->id . '.' . $Request->file('emp_image')->guessClientExtension();
                        Storage::disk('employee')->putFileAs("/".$newEmp->id . '_' . $newEmp->first_name, $file, $filename);
                        $newEmp -> emp_image = 'uploads/Employees/' . $newEmp->id . '_' . $newEmp->first_name . '/' . $filename;
                        $newEmp -> save();
                    }

                    $newUser = new User();
                    $newUser -> employee_id = $newEmp->id;
                    $newUser -> name =  $newEmp->first_name . ' ' . $newEmp->last_name;
                    $newUser -> email = $newEmp->system_email;
                    $newUser -> password =  Hash::make($newEmp->system_email);
                    $newUser -> status  = 1;
                    $newUser -> save();

                    $role = Role::where('blug',$newEmp->position->department->name)->first();
                    $newRole = new Role();
                    $newRole -> title = $role->title;
                    $newRole -> active = 1;
                    $newRole -> blug = $role->blug;
                    $newRole -> content = 'Custom Role for ' .$newEmp->position->department->name;
                    $newRole -> usr_id = $newUser->id;
                    $newRole -> save();

                    $this->addSalary($Request, $newEmp->id);
                    $this->addPermAddress($Request, $newEmp->id);
                    $this->addTempAddress($Request, $newEmp->id);
                    $this->addPhone($Request, $newEmp->id);
                    $this->addEmergency($Request, $newEmp->id);

                    $this->AddToLog('New Employee Added', $newEmp->id , $newEmp->first_name . ' ' . $newEmp->last_name);

                    return response()->json($newEmp->id);
                } else {return response()->json('Email Error');}
            }
            else
            {
                $Countries = Countrie::where('status',1)->get();
                $departments = Department::all();
                $Positions = Position::all();
                $job_types = Job_type::all();
                $job_statuses = Job_status::all();
                $arr = array('Countries' => $Countries, 'departments' => $departments, 'Positions' => $Positions, 'job_types' => $job_types, 'job_statuses' => $job_statuses);

                return view('hr/AddEmployee',$arr);
            }
        } else {return view('errors/AccessDenied');}
    }

/////////////////////////////////////////////// All Employees ///////////////////////////////////////////
    public function ViewEmployees()
    {
        if ($this->CheckPermission('ManageEmp') == true)
        {
            if (!empty(Auth::user()->roles->where('title','Admin_role')->first()))
            {
                $Users = User::join('employees','employees.id','=','users.employee_id')->whereIn('employees.status_id',[1,2])->select('users.*')->get();
            }
            elseif (!empty(Auth::user()->roles->where('title','HR_role')->first()))
            {
                $Users = User::join('employees','employees.id','=','users.employee_id')
                            ->join('roles','roles.user_id','=','users.id')
                            ->whereIn('employees.status_id',[1,2])->where('roles.title','<>','Admin_role')->select('users.*')->get();
            }
            else
            {
                $RolesUser = Role::join('users','users.id','=','roles.user_id')->where('roles.user_id',Auth::user()->id)->select('roles.title')->get();

                $Users = User::join('roles','roles.user_id','=','users.id')->join('employees','employees.id','=','users.employee_id')->whereIn('employees.status_id',[1,2])->whereIn('roles.title',$RolesUser)->select('users.*')->get();
            }

            $job_statuses = Job_status::all();
            $branch_name = Auth::user()->employee->position->department->branch->value('name');
            $arr = array('Users' => $Users, 'job_statuses' => $job_statuses);
            return view('hr/Employees',$arr);
        } else {return view('errors/AccessDenied');}
    }

    public function ChangeEmpStatus(Request $Request)
    {
        if ($this->CheckPermission('ChangeStatus') == true)
        {
            $EditEmp = Employee::find($Request->input('employee_id'));
            $EditEmp -> status_id = $Request->input('status_id');
            $EditEmp -> save();
        } else {return view('errors/404');}
    }

    public function AddEmpSign(Request $Request)
    {

        if ($this->CheckPermission('AddSignEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $Employee = Employee::where('id', $Request->Employee_id)->first();
                $Employee -> sign = 'Employee_Signs/'.$Employee->id.'/Employee_Sign.png';
                $Employee -> save();
                Storage::disk('local')->putFileAs('Employee_Signs/'.$Employee->id.'/', $Request->image, 'Employee_Sign.png');

                return redirect('/Employees');
            } else{return view('hr/Signature');}
        } else {return view('errors/404');}
    }

////////////////////////////////////////// Employee Profile ////////////////////////////////////////
    public function EmployeeProfile(Request $Request)
    {
        if ($this->CheckPermission('ViewEmp') == true)
        {
            $branch_id = Auth::user()->employee->position->department->branch->value('id');
            $branch_name = Auth::user()->employee->position->department->branch->value('name');

            if (!empty(Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()))
            {
                $Employee = Employee::where('id',$Request->id)->first();
            }
            elseif (Auth::user()->employee->position->department->code == Employee::where('id',$Request->id)->first()->position->department->code)
            {
                $Employee = Employee::where('id',$Request->id)->first();
            }
            else {
                return view('errors/AccessDenied');
            }

            $arr = array('branch_name' => $branch_name, 'Employee' => $Employee);
            return view('hr/EmployeeProfile',$arr);
        } else{return view('errors/AccessDenied');}
    }

//////////////////////////////////// Edit Employee ////////////////////////////////////////////////
    public function EditEmployee(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            if ($Request->isMethod('post'))
            {
                $EditEmp = Employee::where('id',$Request->input('Employee_id'))->first();
                $EditEmp -> prefix = $Request->input('prefix');
                $EditEmp -> first_name = $Request->input('first_name');
                $EditEmp -> middle_name = $Request->input('middle_name');
                $EditEmp -> last_name = $Request->input('last_name');
                $EditEmp -> mother = $Request->input('mother');
                $EditEmp -> birthdate = $Request->input('birthdate');
                $EditEmp -> blood = $Request->input('blood');
                $EditEmp -> gender = $Request->input('gender');  //female=0, male=1
                $EditEmp -> marital_status = $Request->input('marital_status');  //sin=1,mar=2,div=3,wid=4
                $EditEmp -> country_id = $Request->input('Nationality');
                $EditEmp -> national_no = $Request->input('national_no');
                $EditEmp -> passport = $Request->input('passport');
                $EditEmp -> email = $Request->input('email');
                $EditEmp -> system_email = $Request->input('system_email');
                $EditEmp -> line = $Request->input('line');
                $EditEmp -> hire_date = $Request->input('hire_date');
                $EditEmp -> resignation_date = $Request->input('resignation_date');
                $EditEmp -> job_type_id = $Request->input('job_type_id');
                $EditEmp -> status_id = $Request->input('status_id');
                $EditEmp -> position_id = $Request->input('position');
                $EditEmp -> save();

                if ($Request->hasFile('emp_image'))
                {
                    $file = $Request->file('emp_image');
                    $filename ='ProfilePic_'. $EditEmp->id . '.' . $Request->file('emp_image')->guessClientExtension();
                    Storage::disk('Employees')->putFileAs("/".$EditEmp->id . '_' . $EditEmp->first_name, $file, $filename);
                    $EditEmp -> emp_image = 'Uploads/Employees/' . $EditEmp->id . '_' . $EditEmp->first_name . '/' . $filename;
                    $EditEmp -> save();
                }

                $user = User::where('employee_id', $Request->Employee_id)->first();
                $user -> email = $EditEmp->system_email;
                $user -> save();

                //Address //Perm
                if ($EditEmp->addresses->where('add_type',1)->count() <= 0)
                {
                    $this->addPermAddress($Request, $EditEmp->id);
                }
                else
                {
                    $this->editPermAddress($Request, $EditEmp->id);
                }
                //Address //Temp
                if ($EditEmp->addresses->where('add_type',2)->count() <= 0)
                {
                    $this->addTempAddress($Request, $EditEmp->id);
                }
                else
                {
                    $this->editTempAddress($Request, $EditEmp->id);
                }

                //Phone
                if ($EditEmp->phones->count() <= 0)
                {
                    $this->addPhone($Request, $EditEmp->id);
                }
                else
                {
                    Phone::where('employee_id',$EditEmp->id)->delete();
                    $this->addPhone($Request, $EditEmp->id);
                }
                //Emergency
                if ($EditEmp->emergencies->count() <= 0)
                {
                    $this->addEmergency($Request, $EditEmp->id);
                }
                else
                {
                    $this->editEmergency($Request, $EditEmp->id);
                }

                $this->AddToLog('Employee Modified', $EditEmp->id, $EditEmp->first_name . ' ' . $EditEmp->last_name);

                return response()->json($Request->Employee_id);
            }
            else
            {
                $Countries = Countrie::where('status',1)->get();
                $job_types = Job_type::all();
                $job_statuses = Job_status::all();
                $Employee = Employee::where('id',$Request->Employee_id)->first();
                $branch_id = Auth::user()->employee->position->department->branch->value('id');
                $department_ids = Department::where('branch_id',$branch_id)->select('id')->get();
                $position_ids = Position::whereIn('department_id',$department_ids)->select('id')->get();
                $Employees = Employee::whereIn('position_id',$position_ids)->get();
                $departments = Department::all();
                $arr = array('Countries' => $Countries, 'Employee' => $Employee, 'Employees' => $Employees, 'departments' => $departments, 'job_types' => $job_types, 'job_statuses' => $job_statuses);
                return view('hr/EditEmployee',$arr);
            }
        } else{return view('errors/AccessDenied');}
    }

/////////////////////////////////////////////// Salary //////////////////////////////////////
    public function addSalary(Request $Request ,$employee_id)
    {
        $NewSalary = new Salarie();
        $NewSalary -> basic = $Request->input('basic');
        $NewSalary -> employee_id = $employee_id;
        $NewSalary -> start_date = Carbon::now();
        $NewSalary -> save();
    }

////////////////////////////////////////////////// Address ///////////////////////////////////
    public function addPermAddress($Request,$employee_id)
    {
        if ($Request->input('address') != "")
        {
            $NewAddress = new Addresse();
            $NewAddress -> add_type = 1;
            $NewAddress -> address = $Request->input('address');
            $NewAddress -> country_id = $Request->input('country');
            $NewAddress -> state_id = $Request->input('state');
            $NewAddress -> employee_id = $employee_id;
            $NewAddress -> save();
        }
    }

    public function editPermAddress($Request,$employee_id)
    {
        $EditAddress = Addresse::where('employee_id',$employee_id)->where('add_type',1)->first();
        if ($Request->input('address') != "")
        {
            $EditAddress -> address = $Request->input('address');
            $EditAddress -> country_id = $Request->input('country');
            $EditAddress -> state_id = $Request->input('state');
            $EditAddress -> save();
        }
        else
        {
            $EditAddress -> delete();
        }
    }

    public function addTempAddress($Request,$employee_id)
    {
        if ($Request->input('address2') != "")
        {
            $NewAddress = new Addresse();
            $NewAddress -> add_type = 2;
            $NewAddress -> address = $Request->input('address2');
            $NewAddress -> country_id = $Request->input('country2');
            $NewAddress -> state_id = $Request->input('state2');
            $NewAddress -> employee_id = $employee_id;
            $NewAddress -> save();
        }
    }

    public function editTempAddress($Request,$employee_id)
    {
        $EditAddress = Addresse::where('employee_id',$employee_id)->where('add_type',2)->first();
        if ($Request->input('address2') != "")
        {
            $EditAddress -> address = $Request->input('address2');
            $EditAddress -> country_id = $Request->input('country2');
            $EditAddress -> state_id = $Request->input('state2');
            $EditAddress -> save();
        }
        else
        {
            $EditAddress -> delete();
        }
    }

/////////////////////////////////// Phones ////////////////////////////////////////////
    public function addPhone($Request,$employee_id)
    {
        //Phone
        for ($i=0; $i < $Request->input('count_phone') ; $i++)
        {
            $NewPhone = new Phone();
            $NewPhone -> phone_type = $Request->phones[$i]['phone_type'];
            $NewPhone -> number = $Request->phones[$i]['number'];
            $NewPhone -> employee_id = $employee_id;
            $NewPhone -> save();
        }
    }

/////////////////////////////////////// Emergency //////////////////////////////////////////
    public function addEmergency($Request,$employee_id)
    {
        //Emergency
        $newEmergencie = new Emergencie();
        $newEmergencie -> fname_emer = $Request->input('fname_emer');
        $newEmergencie -> lname_emer = $Request->input('lname_emer');
        $newEmergencie -> relationship = $Request->input('relationship');
        $newEmergencie -> house_phone = $Request->input('house_phone');
        $newEmergencie -> mobile_phone = $Request->input('mobile_phone');
        $newEmergencie -> employee_id = $employee_id;
        $newEmergencie -> save();
    }

    public function editEmergency($Request,$employee_id)
    {
        //Emergency
        $editEmergencie = Emergencie::where('employee_id',$employee_id)->first();
        $editEmergencie -> fname_emer = $Request->input('fname_emer');
        $editEmergencie -> lname_emer = $Request->input('lname_emer');
        $editEmergencie -> relationship = $Request->input('relationship');
        $editEmergencie -> house_phone = $Request->input('house_phone');
        $editEmergencie -> mobile_phone = $Request->input('mobile_phone');
        $editEmergencie -> save();
    }

///////////////////////// To Feed Ajax //////////////////////////////////////////
    public function getStateList(Request $request)
    {
        $states = State::where("country_id",$request->country_id)->pluck("name","id");
        return response()->json($states);
    }

    public function getPositionList(Request $request)
    {
        $positions = Position::where("department_id",$request->department_id)->pluck("name","id");
        return response()->json($positions);
    }

    public function getManagerList(Request $request)
    {
        $branch_id = Auth::user()->employee->position->department->branch->value('id');
        $department_ids = Department::where('branch_id',$branch_id)->select('id')->get();
        $position_ids = Position::where('department_id',$request->department_id)->orWhere('code' ,'CEO')->orwhere('code' , 'like'  ,'Chief %')->whereIn('department_id', $department_ids)->select('id')->get();
        $employees = Employee::whereIn('position_id',$position_ids)->select('id', DB::raw("CONCAT(first_name,' ',last_name) AS full_name"))->get()->pluck('full_name','id');

        return response()->json($employees);
    }

    public function getEmployeeList(Request $request)
    {
        $employees = Employee::where('position_id',$request->position_id)->select('id', DB::raw("CONCAT(first_name,' ',last_name) AS full_name"))->get()->pluck('full_name','id');
        return response()->json($employees);
    }
}
