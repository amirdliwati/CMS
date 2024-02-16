<?php

namespace App\Http\Controllers\mutual;

use Controller,Auth,Carbon;
use Illuminate\Http\Request;
use App\Models\{Currencie,Employee,Attendance};
use App\Models\zkteco\{Iclock_transaction};
use Illuminate\Notifications\DatabaseNotification;

class HomeController extends Controller
{
    ///////////////////////////////////////////////  Notifications  //////////////////////////////////
    public function Notifications()
    {
        $Notifications = DatabaseNotification::where('notifiable_id',Auth::user()->id)->limit(500)->get();
        $arr = array('Notifications' => $Notifications);
        return view('mutual/Notifications',$arr);
    }

    public function StatusNotify(Request $Request)
    {
        $Notify = DatabaseNotification::find($Request->NotifyID);
        if (Auth::user()->id == $Notify->notifiable_id)
        {
            if(empty($Notify->read_at))
            {
                $Notify -> read_at = Carbon::now();
                $Notify -> save();
                return response()->json('Seen');
            } else {
                $Notify -> read_at = NULL;
                $Notify -> save();
                return response()->json('Unseen');
            }
        }
        else
        {
            return response()->json('Access Denied');
        }
    }

    ///////////////////////////////////////////////  Home  //////////////////////////////////
    public function AdminHome()
    {
        $Currencies = Currencie::where('status',1)->get();

        $arr = array('Currencies' => $Currencies);

        return $arr;
    }

    public function HrHome()
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

            $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);

        }
        elseif ($Attendances->last()->transaction_id <= $Iclock_transactions->last()->id) {
            $count = Iclock_transaction::where('id','>', $Attendances->last()->transaction_id)->count();
            //dd($Employees->where('id',$Iclock_transactions->find(807)->emp_code)->first());
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

            $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);
        }
        else
        {
            $arr = array('Attendances' => Attendance::whereYear('punch_time',Carbon::now()->year)->whereMonth('punch_time',Carbon::now()->format('m'))->whereDay('punch_time',Carbon::now()->format('d'))->get() , 'Employees' => $Employees);
        }

        return $arr;
    }

    public function FinancialHome()
    {
        $arr = array();

        return $arr;
    }

    public function InventoryHome()
    {
        $arr = array();

        return $arr;
    }

    public function TrainingHome()
    {
        $arr = array();

        return $arr;
    }

}
