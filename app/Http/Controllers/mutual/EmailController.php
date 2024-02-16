<?php

namespace App\Http\Controllers\mutual;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Email,Trainee};

class EmailController extends Controller
{
    public function ViewEmails(Request $Request)
    {
        if ($this->CheckPermission('ManageEmails') == true)
        {
            if (!empty(Auth::user()->roles->where('title','Admin_role')->first()) )
            {
                $Emails = Email::limit(100);

            } else {
                $Emails = Email::where('employee_id', Auth::user()->employee->id)->limit(100)->get();
            }
            $arr = array('Emails' => $Emails);
            return view('mutual/emails/Emails',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function EmailCompose(Request $Request)
    {
        if ($this->CheckPermission('ManageEmails') == true) {
            if ($Request->isMethod('post')) {

                    return response()->json();

            }else {

                $Trainees = Trainee::whereIn('status',[1,2])->get();
                $arr = array('Trainees' => $Trainees);

                return view('mutual/emails/EmailCompose',$arr);
            }
        }else {
            return view('errors/AccessDenied');
        }
    }

    public function ViewEmailStatus(Request $Request)
    {
        if ($this->CheckPermission('ManageEmails') == true)
        {
            if ($Request->Status == 'All') {
                $Emails = Email::where('employee_id', Auth::user()->employee->id)->limit(100)->get();
            }
            else{
                $Emails = Email::where('employee_id', Auth::user()->employee->id)->where('status',$Request->Status)->limit(100)->get();
            }

            $arr = array('Emails' => $Emails);
            return view('mutual/emails/ViewEmails',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ViewEmailTypes(Request $Request)
    {
        if ($this->CheckPermission('ManageEmails') == true)
        {
            $Emails = Email::where('employee_id', Auth::user()->employee->id)->where('type',$Request->Type)->limit(100)->get();
            $arr = array('Emails' => $Emails);
            return view('mutual/emails/ViewEmails',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ViewEmailsStatus(Request $Request)
    {
        if ($this->CheckPermission('ManageEmails') == true)
        {
            if ($Request->Status == 'All') {
                $Emails = Email::where('employee_id', Auth::user()->employee->id)->get();
            }
            else{
                $Emails = Email::where('employee_id', Auth::user()->employee->id)->where('status',$Request->Status)->get();
            }

            $arr = array('Emails' => $Emails);
            return view('mutual/emails/ViewEmails',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ViewEmailsTypes(Request $Request)
    {
        if ($this->CheckPermission('ManageEmails') == true)
        {
            $Emails = Email::where('employee_id', Auth::user()->employee->id)->where('type',$Request->Type)->get();
            $arr = array('Emails' => $Emails);
            return view('mutual/emails/ViewEmails',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

}
