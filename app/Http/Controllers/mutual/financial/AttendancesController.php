<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth, Carbon;
use Illuminate\Http\Request;
use App\Models\{Attendance,Employee};
use App\Models\zkteco\{Personnel_employee,Iclock_transaction};

class AttendancesController extends Controller
{
    public function Attendances()
    {
        $Employees = Employee::all();
        $AttendancesSync = Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();
        $Iclock_transactions = Iclock_transaction::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();

        if (empty($AttendancesSync->last()->transaction_id)) {

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

            $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->where('employee_id',Auth::user()->employee->id)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employee' => Auth::user()->employee);

        }
        elseif ($AttendancesSync->last()->transaction_id <= $Iclock_transactions->last()->id) {
            $count = Iclock_transaction::where('id','>', $AttendancesSync->last()->transaction_id)->count();

            for ($i = 1 ; $i <= $count ; $i++) {
                if (!empty($Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->emp_code)) {
                    if (!empty($Employees->where('id',$Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->emp_code)->first())) {
                        $newAttendance = new Attendance();
                        $newAttendance -> punch_time = $Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->punch_time;
                        $newAttendance -> punch_state = $Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->punch_state;
                        $newAttendance -> terminal_sn = $Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->terminal_sn;
                        $newAttendance -> terminal_alias = $Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->terminal_alias;
                        $newAttendance -> employee_id = $Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->emp_code;
                        $newAttendance -> transaction_id = $Iclock_transactions->find($AttendancesSync->last()->transaction_id + $i)->id;
                        $newAttendance -> save();
                    }
                }
            }

            $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->where('employee_id',Auth::user()->employee->id)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employee' => Auth::user()->employee);
        }
        else
        {
            $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->where('employee_id',Auth::user()->employee->id)->whereMonth('punch_time',Carbon::now()->format('m'))->get() , 'Employee' => Auth::user()->employee);
        }

        return view('mutual/financial/Attendances',$arr);

    }

    public function SearchMyAttendances(Request $Request)
    {
        if ($Request->employee_id == Auth::user()->employee->id)
        {
            $Attendances = Attendance::where('employee_id',$Request->employee_id)->whereBetween('punch_time', [$Request->start_date , $Request->end_date])->get();
            $response = array();
            foreach($Attendances as $Attendance){
            $response[] = array("id"=>$Attendance->id,
                                "punch_time"=>$Attendance->punch_time,
                                "punch_state" =>[
                                    "id"=>$Attendance->id,
                                    "state"=>$Attendance->punch_state
                                ],
                                "terminal_sn" =>$Attendance->terminal_sn,
                                "terminal_alias"=>$Attendance->terminal_alias,
                                "sync" =>$Attendance->sync);
            }

            return response()->json($response);
        }
        else
        {
            return response()->json("You do not have permission");
        }
    }

}
