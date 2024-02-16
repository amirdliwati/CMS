<?php

namespace App\Http\Controllers\hr;

use Controller, Auth, Carbon;
use Illuminate\Http\Request;
use App\Models\{Attendance,Leave,Leave_type,Leave_role,Employee};
use App\Models\zkteco\{Personnel_employee,Iclock_transaction};

class LeaveAttendanceController extends Controller
{
//////////////////////////////////  Leaves ///////////////////////////////
    public function Leaves(Request $Request)
    {
        if ($this->CheckPermission('ManageEmp') == true)
        {
            $Employee = Employee::find($Request->idEmployee);
            $Leaves = Leave::where('employee_id',$Request->idEmployee)->get();
            $arr = array('Leaves' => $Leaves , 'Employee' => $Employee);
            return view('hr/leave_attendance/Leaves',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function LeaveRole(Request $Request)
    {
        if ($this->CheckPermission('ManageEmp') == true)
        {
            if ($Request->input('leave_role_id') == 0)
            {
                $newLeaveRole = new Leave_role();
                $newLeaveRole -> days_count = $Request->input('days_count');
                $newLeaveRole -> hours_count = $Request->input('hours_count');
                $newLeaveRole -> remain_days = $Request->input('days_count');
                $newLeaveRole -> remain_hours = $Request->input('hours_count');
                $newLeaveRole -> employee_id = $Request->input('employee_id');
                $newLeaveRole -> save();

                $this->AddToLog('Added New Leave Role',$newLeaveRole->employee_id , $newLeaveRole->id);

                $Request->session()->put('msgSuccess', 'The leave role has been added successfully');
            }
            else
            {
                $editLeaveRole = Leave_role::find($Request->input('leave_role_id'));
                if ($editLeaveRole->days_count < $Request->input('days_count')) {
                    $editLeaveRole -> remain_days = ($editLeaveRole->remain_days) + ($Request->input('days_count') - $editLeaveRole->days_count);
                }
                elseif ($editLeaveRole->days_count > $Request->input('days_count')) {
                    $editLeaveRole -> remain_days = ($editLeaveRole->remain_days) - ($editLeaveRole->days_count - $Request->input('days_count'));
                }
                else {
                    $editLeaveRole -> remain_days = $editLeaveRole->remain_days;
                }

                if ($editLeaveRole->hours_count < $Request->input('hours_count')) {
                    $editLeaveRole -> remain_hours = ($editLeaveRole->remain_hours) + ($Request->input('hours_count') - $editLeaveRole->hours_count);
                }
                elseif ($editLeaveRole->hours_count > $Request->input('hours_count')) {
                    $editLeaveRole -> remain_hours = ($editLeaveRole->remain_hours) - ($editLeaveRole->hours_count - $Request->input('hours_count'));
                }
                else {
                    $editLeaveRole -> remain_hours = $editLeaveRole->remain_hours;
                }
                $editLeaveRole -> days_count = $Request->input('days_count');
                $editLeaveRole -> hours_count = $Request->input('hours_count');
                $editLeaveRole -> save();

                $this->AddToLog('Edited Leave Role',$editLeaveRole->employee_id , $editLeaveRole->id);

                $Request->session()->put('msgWarning', 'The leave role has been edited successfully');
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

//////////////////////////////////  Attendances ///////////////////////////////
    public function ViewAttendances()
    {
        if ($this->CheckPermission('ManageEmp') == true)
        {
            $Employees = Employee::all();
            $Attendances = Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();
            $Iclock_transactions = Iclock_transaction::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();

            if (empty($Attendances->last()->transaction_id)) {

                foreach ($Iclock_transactions as $Iclock_transaction) {
                    if (!empty($Iclock_transaction->emp_code)) {
                        if (!empty($Employees->where('id',$Iclock_transaction->emp_code)->first())) {
                            $newAttendance = new Attendance();
                            $newAttendance -> punch_time = $Iclock_transaction->punch_time;
                            $newAttendance -> punch_state = $Iclock_transaction->punch_state;
                            $newAttendance -> terminal_sn = $Iclock_transaction->terminal_sn;
                            $newAttendance -> terminal_alias = $Iclock_transaction->terminal_alias;
                            $newAttendance -> employee_id = $Iclock_transaction->emp_code;
                            $newAttendance -> transaction_id = $Iclock_transaction->id;
                            $newAttendance -> save();
                        }
                    }
                }

                $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employees' => $Employees);

            }
            elseif ($Attendances->last()->transaction_id <= $Iclock_transactions->last()->id) {
                $count = Iclock_transaction::where('id','>', $Attendances->last()->transaction_id)->count();

                for ($i = 1 ; $i <= $count ; $i++) {
                    if (!empty($Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code)) {
                        if (!empty($Employees->where('id',$Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code)->first())) {
                            $newAttendance = new Attendance();
                            $newAttendance -> punch_time = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->punch_time;
                            $newAttendance -> punch_state = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->punch_state;
                            $newAttendance -> terminal_sn = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->terminal_sn;
                            $newAttendance -> terminal_alias = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->terminal_alias;
                            $newAttendance -> employee_id = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code;
                            $newAttendance -> transaction_id = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->id;
                            $newAttendance -> save();
                        }
                    }
                }

                $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employees' => $Employees);
            }
            else
            {
                $arr = array('Attendances' => $Attendances , 'Employees' => $Employees);
            }
            return view('hr/leave_attendance/Attendances',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function SearchEmployeeAttendance(Request $Request)
    {
        if ($this->CheckPermission('ManageEmp') == true || $this->CheckPermission('ManagementFinancialEmployees') == true)
        {
            $Attendances = Attendance::where('employee_id',$Request->employee_id)->whereBetween('punch_time', [$Request->start_date , $Request->end_date])->get();
            $response = array();
            foreach($Attendances as $Attendance){
            $response[] = array("id"=>$Attendance->id,
                                "employee_name"=>$Attendance->employee->first_name." ".$Attendance->employee->middle_name." ".$Attendance->employee->last_name,
                                "department"=>$Attendance->employee->position->department->name,
                                "punch_time"=>$Attendance->punch_time,
                                "punch_state_hidden"=>$Attendance->punch_state,
                                "punch_state" =>[
                                    "id"=>$Attendance->id,
                                    "state"=>$Attendance->punch_state
                                ],
                                "terminal_sn" =>$Attendance->terminal_sn,
                                "terminal_alias"=>$Attendance->terminal_alias,
                                "modify" =>[
                                    "id"=>$Attendance->id,
                                    "sync"=>$Attendance->sync]);
            }

            return response()->json($response);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ChangeAttendanceSync(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            $sync = Attendance::find($Request->AttendanceID);

                if ($sync->sync == 1) {
                    $sync -> sync = 0;
                    $sync -> save();
                    return response()->json("fail");
                } else{
                    $sync -> sync = 1;
                    $sync -> save();
                    return response()->json("pass");
                }
        }
        else
        {
            return response()->json("Access Denied");
        }
    }

    public function ChangePunchState(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            $PunchState = Attendance::find($Request->AttendanceID);

                if ($Request->PunchState == 0) {
                    $PunchState -> punch_state = 0;
                    $PunchState -> save();
                    return response()->json("Check IN");
                }
                else if ($Request->PunchState == 1) {
                    $PunchState -> punch_state = 1;
                    $PunchState -> save();
                    return response()->json("Check Out");
                }
                else if ($Request->PunchState == 3) {
                    $PunchState -> punch_state = 3;
                    $PunchState -> save();
                    return response()->json("Break IN");
                }
                else if ($Request->PunchState == 2) {
                    $PunchState -> punch_state = 2;
                    $PunchState -> save();
                    return response()->json("Break Out");
                }
                else if ($Request->PunchState == 4) {
                    $PunchState -> punch_state = 4;
                    $PunchState -> save();
                    return response()->json("Overtime IN");
                }
                else if ($Request->PunchState == 5) {
                    $PunchState -> punch_state = 5;
                    $PunchState -> save();
                    return response()->json("Overtime Out");
                }
        }
        else
        {
            return response()->json("Access Denied");
        }
    }
}
