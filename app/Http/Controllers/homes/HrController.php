<?php

namespace App\Http\Controllers\homes;

use Controller, Carbon;
use App\Models\{Employee,Legal_doc,Attendance};
//use App\Models\zkteco\{Iclock_transaction};

class HrController extends Controller
{
    //////////////////////////////  Filters  ///////////////////////////////////
    public function Filters()
    {
        $Employees = Employee::all();

        $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);

        return $arr;

        // $Employees = Employee::all();
        // $Attendances = Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();
        // $Iclock_transactions = Iclock_transaction::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->get();

        // if (empty($Attendances->last()->transaction_id)) {
        //     foreach ($Iclock_transactions as $Iclock_transaction) {
        //         if (!empty($Iclock_transaction->emp_code)) {
        //             if (!empty($Employees->where('id',$Iclock_transaction->emp_code)->first())) {
        //                 $newAttendance = new Attendance();
        //                 $newAttendance -> punch_time = $Iclock_transaction->punch_time;
        //                 $newAttendance -> punch_state = $Iclock_transaction->punch_state;
        //                 $newAttendance -> terminal_sn = $Iclock_transaction->terminal_sn;
        //                 $newAttendance -> terminal_alias = $Iclock_transaction->terminal_alias;
        //                 $newAttendance -> employee_id = $Iclock_transaction->emp_code;
        //                 $newAttendance -> transaction_id = $Iclock_transaction->id;
        //                 $newAttendance -> save();
        //             }
        //         }
        //     }

        //     $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);

        // }
        // elseif ($Attendances->last()->transaction_id <= $Iclock_transactions->last()->id) {
        //     $count = Iclock_transaction::where('id','>', $Attendances->last()->transaction_id)->count();
        //     //dd($Employees->where('id',$Iclock_transactions->find(807)->emp_code)->first());
        //     for ($i = 1 ; $i <= $count ; $i++) {
        //         if (!empty($Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code)) {
        //             if (!empty($Employees->where('id',$Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code)->first())) {
        //                 $newAttendance = new Attendance();
        //                 $newAttendance -> punch_time = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->punch_time;
        //                 $newAttendance -> punch_state = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->punch_state;
        //                 $newAttendance -> terminal_sn = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->terminal_sn;
        //                 $newAttendance -> terminal_alias = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->terminal_alias;
        //                 $newAttendance -> employee_id = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->emp_code;
        //                 $newAttendance -> transaction_id = $Iclock_transactions->find($Attendances->last()->transaction_id + $i)->id;
        //                 $newAttendance -> save();
        //             }
        //         }
        //     }

        //     $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);
        // }
        // else
        // {
        //     $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);
        // }

        // return $arr;
    }

    public function Notifications()
    {
        $LegaldocNotifications = Legal_doc::join('employees','employees.id','=','legal_docs.employee_id')
        ->select('legal_docs.*','employees.*')->get();

        $arr = array('LegaldocNotifications' => $LegaldocNotifications);

        return $arr;

    }

}
