<?php

namespace App\Http\Controllers\financial\reports;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Account,Employee,Payroll,Receipts_book};

class ReportsController extends Controller
{
/////////////////////////////////////  Employees Report  ///////////////////////////////////////////////////////
    public function EmployeessReport()
    {
        if ($this->CheckPermission('ReportsFinancialEmployees') == true)
        {
            $Employees = Employee::where('account_id','<>',NULL)->get()->unique('account_id');
            $arr = array('Employees' => $Employees);
            return view('financial/reports/PayrollsReport',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ReportSearchPayrolls(Request $Request)
    {
        if ($this->CheckPermission('ReportsFinancialEmployees') == true)
        {
            $Account = Account::find($Request->employee_account);
            $Debit = 0;
            $Credit = 0;

            $response = array();
            foreach ($Account->employees as $Employee) {
                $Payrolls = Payroll::where('employee_id',$Employee->id)->get();
                foreach($Payrolls as $Payroll){

                    if (empty($Payroll->signature)) {
                        $Debit += $Payroll->total / $Payroll->currencie->dollar_buy;
                    }
                    else{
                        $Credit += $Payroll->total / $Payroll->currencie->dollar_buy;
                    }

                $response[] = array("id"=>$Payroll->id,
                                    "payroll_date"=>$Payroll->date,
                                    "total_payroll" =>$Payroll->currencie->symbol."  ".$Payroll->total,
                                    "salary" =>$Payroll->currencie->symbol."  ".$Payroll->salary->basic,
                                    "employee_name"=>$Payroll->employee->first_name." ".$Payroll->employee->last_name,
                                    "notes"=>$Payroll->notes,
                                    "employee_name_issued"=>$Payroll->employee_issued->first_name." ".$Payroll->employee_issued->last_name,
                                    "status"=>$Payroll->signature);
                }
            }
            $arr = array('response' => $response , 'debit' => $Debit , 'credit' => $Credit);
            return response()->json($arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ReportsPayrolls(Request $Request)
    {
        if ($this->CheckPermission('ReportsFinancialEmployees') == true)
        {
            if ($Request->AccountID == 0) {
                $response = array();
                $Employees = Employee::where('account_id','<>',NULL)->get();
                $Debit = 0;
                $Credit = 0;

                foreach ($Employees as $Employee) {
                    $Payrolls = Payroll::where('employee_id',$Employee->id)->whereBetween('date', [$Request->From, $Request->To])->get();
                    foreach($Payrolls as $Payroll){

                        if (empty($Payroll->signature)) {
                            $Debit += $Payroll->total / $Payroll->currencie->dollar_buy;
                        }
                        else{
                            $Credit += $Payroll->total / $Payroll->currencie->dollar_buy;
                        }

                        $response[] = array("id"=>$Payroll->id,
                                    "payroll_date"=>$Payroll->date,
                                    "total_payroll" =>$Payroll->currencie->symbol."  ".$Payroll->total,
                                    "salary" =>$Payroll->currencie->symbol."  ".$Payroll->salary->basic,
                                    "employee_name"=>$Payroll->employee->first_name." ".$Payroll->employee->last_name,
                                    "notes"=>$Payroll->notes,
                                    "employee_name_issued"=>$Payroll->employee_issued->first_name." ".$Payroll->employee_issued->last_name,
                                    "status"=>$Payroll->signature);
                    }
                }
            }
            else {
                $Account = Account::find($Request->AccountID);
                $Debit = 0;
                $Credit = 0;

                $response = array();
                foreach ($Account->employees as $Employee) {
                    $Payrolls = Payroll::where('employee_id',$Employee->id)->whereBetween('date', [$Request->From, $Request->To])->get();
                    foreach($Payrolls as $Payroll){
                        if (empty($Payroll->signature)) {
                            $Debit += $Payroll->total / $Payroll->currencie->dollar_buy;
                        }
                        else{
                            $Credit += $Payroll->total / $Payroll->currencie->dollar_buy;
                        }

                    $response[] = array("id"=>$Payroll->id,
                                    "payroll_date"=>$Payroll->date,
                                    "total_payroll" =>$Payroll->currencie->symbol."  ".$Payroll->total,
                                    "salary" =>$Payroll->currencie->symbol."  ".$Payroll->salary->basic,
                                    "employee_name"=>$Payroll->employee->first_name." ".$Payroll->employee->last_name,
                                    "notes"=>$Payroll->notes,
                                    "employee_name_issued"=>$Payroll->employee_issued->first_name." ".$Payroll->employee_issued->last_name,
                                    "status"=>$Payroll->signature);
                    }
                }
            }
            $arr = array('response' => $response , 'debit' => $Debit , 'credit' => $Credit);
            return response()->json($arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

/////////////////////////////////////  Receipts Books Report  ///////////////////////////////////////////////////////
    public function ReceiptsBooksReport()
    {
        if ($this->CheckPermission('ReportsFinancialReceiptsBooks') == true)
        {
            $ReceiptsBooks = Receipts_book::where('account_id','<>',NULL)->get()->unique('account_id');
            $arr = array('ReceiptsBooks' => $ReceiptsBooks);
            return view('financial/reports/ReceiptsBooksReport',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ReportSearchReceiptsBooks(Request $Request)
    {
        if ($this->CheckPermission('ReportsFinancialReceiptsBooks') == true)
        {
            $Account = Account::find($Request->receipt_book_account);
            $Debit = 0;
            $Credit = 0;

            $response = array();
            foreach ($Account->receipts_books as $ReceiptsBook) {
                if ($ReceiptsBook->type == 2) {
                    $Debit += $ReceiptsBook->amount / $ReceiptsBook->currencie->dollar_buy;
                }
                else{
                    $Credit += $ReceiptsBook->amount / $ReceiptsBook->currencie->dollar_buy;
                }

                $response[] = array("id"=>$ReceiptsBook->id,
                                    "serial_number"=>[
                                        "receipt_id"=>$ReceiptsBook->id,
                                        "S/N"=>$ReceiptsBook->serial_number,
                                        "status"=>$ReceiptsBook->status],
                                    "date"=>$ReceiptsBook->date,
                                    "customer"=>$ReceiptsBook->customer,
                                    "status"=>$ReceiptsBook->status,
                                    "employee"=>$ReceiptsBook->employee->first_name.' '.$ReceiptsBook->employee->last_name,
                                    "amount"=>[
                                        "amount_receipt"=>$ReceiptsBook->amount,
                                        "currency"=>$ReceiptsBook->currencie->symbol],
                                    "notes"=>$ReceiptsBook->notes,
                                    "type"=>$ReceiptsBook->type);
            }
            $arr = array('response' => $response , 'debit' => $Debit , 'credit' => $Credit);
            return response()->json($arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ReportsReceiptsBooks(Request $Request)
    {
        if ($this->CheckPermission('ReportsFinancialReceiptsBooks') == true)
        {
            if ($Request->AccountID == 0) {
                $response = array();
                $Debit = 0;
                $Credit = 0;

                $ReceiptsBooks = Receipts_book::where('account_id','<>',NULL)->whereBetween('date', [$Request->From, $Request->To])->get();
                foreach($ReceiptsBooks as $ReceiptsBook){

                    if ($ReceiptsBook->type == 2) {
                        $Debit += $ReceiptsBook->amount / $ReceiptsBook->currencie->dollar_buy;
                    }
                    else{
                        $Credit += $ReceiptsBook->amount / $ReceiptsBook->currencie->dollar_buy;
                    }

                    $response[] = array("id"=>$ReceiptsBook->id,
                                "serial_number"=>[
                                    "receipt_id"=>$ReceiptsBook->id,
                                    "S/N"=>$ReceiptsBook->serial_number,
                                    "status"=>$ReceiptsBook->status],
                                "date"=>$ReceiptsBook->date,
                                "customer"=>$ReceiptsBook->customer,
                                "status"=>$ReceiptsBook->status,
                                "employee"=>$ReceiptsBook->employee->first_name.' '.$ReceiptsBook->employee->last_name,
                                "amount"=>[
                                    "amount_receipt"=>$ReceiptsBook->amount,
                                    "currency"=>$ReceiptsBook->currencie->symbol],
                                "notes"=>$ReceiptsBook->notes,
                                "type"=>$ReceiptsBook->type);
                }
            }
            else {
                $Account = Account::find($Request->AccountID);
                $Debit = 0;
                $Credit = 0;

                $response = array();
                    $ReceiptsBooks = Receipts_book::where('account_id','<>',NULL)->whereBetween('date', [$Request->From, $Request->To])->get();
                foreach($ReceiptsBooks as $ReceiptsBook){
                    if ($ReceiptsBook->type == 2) {
                        $Debit += $ReceiptsBook->amount / $ReceiptsBook->currencie->dollar_buy;
                    }
                    else{
                        $Credit += $ReceiptsBook->amount / $ReceiptsBook->currencie->dollar_buy;
                    }

                    $response[] = array("id"=>$ReceiptsBook->id,
                                    "serial_number"=>[
                                        "receipt_id"=>$ReceiptsBook->id,
                                        "S/N"=>$ReceiptsBook->serial_number,
                                        "status"=>$ReceiptsBook->status],
                                    "date"=>$ReceiptsBook->date,
                                    "customer"=>$ReceiptsBook->customer,
                                    "status"=>$ReceiptsBook->status,
                                    "employee"=>$ReceiptsBook->employee->first_name.' '.$ReceiptsBook->employee->last_name,
                                    "amount"=>[
                                        "amount_receipt"=>$ReceiptsBook->amount,
                                        "currency"=>$ReceiptsBook->currencie->symbol],
                                    "notes"=>$ReceiptsBook->notes,
                                    "type"=>$ReceiptsBook->type);
                }
            }
            $arr = array('response' => $response , 'debit' => $Debit , 'credit' => $Credit);
            return response()->json($arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }
}
