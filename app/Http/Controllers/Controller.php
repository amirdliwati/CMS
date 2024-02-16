<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Auth, Hash;
use Illuminate\Http\Request;
use App\Models\{Log,Role_permission};

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function CheckPermission($title)
    {
        foreach(Auth::user()->roles as $Role){
            if (!empty($Role->permissions->where('title',$title))) {return true;} else {return false;}
        }
    }

    public function AddToLog($title,$operating_code,$operating_name)
    {
        if (Auth::user()->id != 0)
        {
            $addlog = new Log();
            $addlog -> title = $title;
            $addlog -> operating_name = $operating_name;
            $addlog -> operating_code = $operating_code;
            $addlog -> user_id = Auth::user()->id;
            $addlog -> save();
        }
    }

    public function index(Request $Request)
    {
        $PathHome = app('App\Http\Controllers\mutual\HomeController');

        if (Hash::check(Auth::user()->email, Auth::user()->password))
        {
            return route('ChangePassword');
        }

        elseif (!empty(Auth::user()->roles->where('title','Admin_role')->first())){
            $arr = array('Admin' => $PathHome->AdminHome(),
                'HR' => $PathHome->HrHome(),
                'Financial' =>$PathHome->FinancialHome(),
                'Inventory' =>$PathHome->InventoryHome(),
                'Training' =>$PathHome->TrainingHome()
            );
        }

        else {
            $arr = array();
            foreach (Auth::user()->roles->where('active',1) as $Role) {
                if($Role->title == 'HR_role'){
                    $arr['HR'] = $PathHome->HrHome();
                }
                if($Role->title == 'Financial_role'){
                    $arr['Financial'] = $PathHome->FinancialHome();
                }
                if($Role->title == 'Inventory_role'){
                    $arr['Inventory'] = $PathHome->InventoryHome();
                }
                if($Role->title == 'Training_role'){
                    $arr['Training'] = $PathHome->TrainingHome();
                }
            }

        }
        return view('layouts/home',$arr);
    }

}
