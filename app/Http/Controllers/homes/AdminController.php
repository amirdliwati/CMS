<?php

namespace App\Http\Controllers\homes;

use Controller;
use App\Models\{Currencie,Legal_doc};

class AdminController extends Controller
{

    //////////////////////////////  Filters  ///////////////////////////////////
    public function Filters()
    {
        $Currencies = Currencie::where('status',1)->get();

        $arr = array('Currencies' => $Currencies);

        return $arr;
    }

    public function Notifications()
    {
        // HR
            $LegaldocNotifications = Legal_doc::join('employees','employees.id','=','legal_docs.employee_id')
            ->select('legal_docs.*','employees.*')->get();

        $arr = array('LegaldocNotifications' => $LegaldocNotifications);

        return $arr;

    }

}
