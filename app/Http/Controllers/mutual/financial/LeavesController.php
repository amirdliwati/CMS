<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Leave,Leave_type,Leave_role};

class LeavesController extends Controller
{
    public function Leaves(Request $Request)
    {
        if ($Request->isMethod('get'))
        {
            $Leaves = Leave::where('employee_id',Auth::user()->employee->id)->get();
            $Leave_types = Leave_type::all();
            $Leave_roles = Leave_role::where('employee_id',Auth::user()->employee->id)->get();
            $arr = array('Leaves' => $Leaves , 'Employee' => Auth::user()->employee , 'Leave_types' => $Leave_types , 'Leave_roles' => $Leave_roles);
            return view('mutual/financial/Leaves',$arr);
        }
        else
        {
            $newLeave = new Leave();
            $newLeave -> date = $Request->input('date');
            $newLeave -> start_date = $Request->input('start_date');
            $newLeave -> end_date = $Request->input('end_date');
            $newLeave -> duration = $Request->input('duration');
            $newLeave -> notes = $Request->input('notes');
            if ($Request->input('ispaid') == 'on') {
                $newLeave -> ispaid = 1;
            } else{$newLeave -> ispaid = 0;}
            $newLeave -> type_id = $Request->input('type_id');
            $newLeave -> employee_id = Auth::user()->employee->id;
            $newLeave -> save();

            $this->AddToLog('Added New Leave',$newLeave->date , $newLeave->id);

            $Request->session()->put('msgSuccess', 'The Leave has been added successfully');
        }
    }

    public function DeleteLeave(Request $Request)
    {
        $deleteLeave = Leave::find($Request->leaveID);
        $this->AddToLog('Deleted Leave',$deleteLeave->date , $deleteLeave->id);
        $deleteLeave -> delete();

        return response()->json('ok');
    }

    public function GetEmployeeLeaves(Request $Request)
    {
        if ($this->CheckPermission('ManageEmp') == true)
        {
            $Leaves = Leave::where('employee_id',$Request->EmployeeID)->get();
            $response = array();
            foreach($Leaves as $Leave){
            $response[] = array("id"=>$Leave->id,
                                "date"=>$Leave->date,
                                "start_date"=>$Leave->start_date,
                                "end_date"=>$Leave->end_date,
                                "duration_leave"=>[
                                    "duration"=>$Leave->duration,
                                    "start_date"=>$Leave->start_date,
                                    "end_date"=>$Leave->end_date],
                                "ispaid"=>$Leave->ispaid,
                                "approved_by"=>$Leave->approved_by,
                                "type"=>$Leave->leave_type->name,
                                "notes"=>$Leave->notes,
                                "status"=>[
                                    "id"=>$Leave->id,
                                    "approved_by"=>$Leave->approved_by]);
            }

            return response()->json($response);
        }
        else
        {
            return response()->json("You do not have permission");
        }
    }

    public function ChangeLeaveStatus(Request $Request)
    {
        if ($this->CheckPermission('ManageEmp') == true)
        {
            $EditLeave= Leave::find($Request->LeaveID);
            $editLeaveRole = Leave_role::where('employee_id',$EditLeave->employee->id)->get()->last();
            if(empty($EditLeave->approved_by)) {
                $EditLeave-> approved_by = Auth::user()->employee->id;
                $EditLeave-> save();

                if ($EditLeave->start_date != $EditLeave->end_date) {
                    $editLeaveRole -> remain_days = $editLeaveRole->remain_days - $EditLeave->duration;
                }
                else {
                    $editLeaveRole -> remain_hours = $editLeaveRole->remain_hours - $EditLeave->duration;
                }
                $editLeaveRole -> save();

                return response()->json("ok");
            }
            else {
                $EditLeave-> approved_by = NULL;
                $EditLeave-> save();

                if ($EditLeave->start_date != $EditLeave->end_date) {
                    $editLeaveRole -> remain_days = $editLeaveRole->remain_days + $EditLeave->duration;
                }
                else {
                    $editLeaveRole -> remain_hours = $editLeaveRole->remain_hours + $EditLeave->duration;
                }
                $editLeaveRole -> save();

                return response()->json("not ok");
            }
        }
        else
        {
            return response()->json("You do not have permission");
        }
    }

    public function ChangeLeaveStatusPaid(Request $Request)
    {
        if ($this->CheckPermission('EditEmp') == true)
        {
            $EditLeave = Leave::find($Request->LeaveID);
            if($EditLeave->ispaid == 0) {
                $EditLeave-> ispaid = 1;
                $EditLeave-> save();
                return response()->json("ok");
            }
            else {
                $EditLeave-> ispaid = 0;
                $EditLeave-> save();
                return response()->json("not ok");
            }
        }
        else
        {
            return response()->json("You do not have permission");
        }
    }

}
