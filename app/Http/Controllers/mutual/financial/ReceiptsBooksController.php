<?php

namespace App\Http\Controllers\mutual\financial;

use Controller, Auth, Carbon;
use Illuminate\Http\Request;
use App\Models\{Receipts_book,Currencie,Account,Pdf_template};

class ReceiptsBooksController extends Controller
{
/////////////////////////////////////  Management Receipts Books  ///////////////////////////////////////////////////////
    public function ReceiptsBooks(Request $Request)
    {
        if ($this->CheckPermission('ReceiptsBooksManage') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newReceiptBook = new Receipts_book();
                $newReceiptBook -> customer = $Request->input('customer');
                $newReceiptBook -> date = $Request->input('date');
                $newReceiptBook -> employee_id = Auth::user()->employee->id;
                $newReceiptBook -> status = 2;
                $newReceiptBook -> type = $Request->input('receipt_type');
                $newReceiptBook -> currency_id = $Request->input('currency');
                $newReceiptBook -> amount = $Request->input('amount');
                $newReceiptBook -> notes = $Request->input('notes');
                $newReceiptBook -> amount_write = $Request->input('amount_write');
                $newReceiptBook -> account_id = $Request->input('account_id');
                $newReceiptBook -> pdf_template_id = Pdf_template::where('type','Receipt')->where('status',1)->first()->id;

                $newReceiptBook -> save();

                if ($newReceiptBook->id < 10) {
                    $newReceiptBook -> serial_number = Carbon::now()->isoFormat('YY'). '-' .'00'. $newReceiptBook->id;
                }
                elseif ($newReceiptBook->id < 100) {
                    $newReceiptBook -> serial_number = Carbon::now()->isoFormat('YY'). '-' .'0'. $newReceiptBook->id;
                }
                else {
                    $newReceiptBook -> serial_number = Carbon::now()->isoFormat('YY'). '-' . $newReceiptBook->id;
                }

                $newReceiptBook -> save();

                $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::parse($Request->Date)->format('Y'))->whereMonth('date',Carbon::parse($Request->Date)->format('m'))->get();

                return response()->json($this->DataTableReceiptBooks($ReceiptsBooks));
            }
            else
            {
                $Accounts = Account::where('type', 0)->get();
                $Currencies = Currencie::where('status',1)->get();
                $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->get();
                $arr = array('ReceiptsBooks' => $ReceiptsBooks , 'Currencies' => $Currencies , 'Accounts' => $Accounts);
                return view('mutual/financial/ReceiptsBooks',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function StatusReceiptsBooks(Request $Request)
    {
        if ($Request->isMethod('post'))
        {
            $ReceiptBook = Receipts_book::find($Request->input('receipt_id'));
            $ReceiptBook -> status = 3;
            $ReceiptBook -> notes_cancel = $Request->input('notes_cancel');
            $ReceiptBook -> employee_cancel_id  = Auth::user()->employee->id;
            $ReceiptBook -> save();

        }
        else
        {
            $ReceiptBook = Receipts_book::find($Request->ReceiptID);
            $ReceiptBook -> status = 2;
            $ReceiptBook -> notes_cancel = null;
            $ReceiptBook -> employee_cancel_id  = null;
            $ReceiptBook -> save();

        }

        $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->get();
        return response()->json($this->DataTableReceiptBooks($ReceiptsBooks));
    }

    public function DataTableReceiptBooks($ReceiptsBooks)
    {
        $response = array();
        foreach($ReceiptsBooks as $ReceiptsBook){

            if(!empty($ReceiptsBook->employee_cancel_id)) {
                $employee_canceled = $ReceiptsBook->employee_cancel->first_name.' '.$ReceiptsBook->employee_cancel->last_name;
            } else {$employee_canceled = null;}

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
                            "type"=>$ReceiptsBook->type,
                            "canceled"=>[
                                "employee_canceled"=>$employee_canceled,
                                "notes_canceled"=>$ReceiptsBook->notes_cancel],
                            "controller"=>[
                                "id_receipt"=>$ReceiptsBook->id,
                                "status_receipt"=>$ReceiptsBook->status
                            ]);
        }

        return $response;
    }

/////////////////////////////////////  Search Receipts Books  ///////////////////////////////////////////////////////
    public function SearchReceiptBooksByMonth(Request $Request)
    {
        $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::parse($Request->Date)->format('Y'))->whereMonth('date',Carbon::parse($Request->Date)->format('m'))->get();

        return response()->json($this->DataTableReceiptBooks($ReceiptsBooks));
    }

    public function GetReceiptsBooks(Request $Request)
    {
        $ReceiptsBooks = Receipts_book::where('type',$Request->Type)->limit(300)->get();

        return response()->json($this->DataTableReceiptBooks($ReceiptsBooks));
    }

}
