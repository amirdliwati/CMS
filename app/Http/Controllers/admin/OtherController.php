<?php

namespace App\Http\Controllers\admin;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Countrie};

class OtherController extends Controller
{
//////////////////////////////   Country  ///////////////////////////////////
    public function Countries()
    {
        if ($this->CheckPermission('CountryAdmin') == true)
        {
            $Countries = Countrie::all();
            $arr = array('Countries' => $Countries);
            return view('admin/other/Countries',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ChangeStatusCountry(Request $Request)
    {
        if ($this->CheckPermission('CountryAdmin') == true)
        {
            $editCountry = Countrie::find($Request->CountryID);
            if ($editCountry->status == 1) {
                $editCountry -> status = 0;
                $editCountry -> save();
                return response()->json('off');
            } else {
                $editCountry -> status = 1;
                $editCountry -> save();
                return response()->json('success');
            }

        }
        else
        {
            return response()->json('Access Denied');
        }
    }
}
