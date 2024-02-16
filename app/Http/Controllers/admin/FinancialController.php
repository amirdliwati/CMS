<?php

namespace App\Http\Controllers\admin;

use Controller, Auth, Carbon;
use Illuminate\Http\Request;
use App\Models\{Currencie,Receipts_book};

class FinancialController extends Controller
{
//////////////////////////////   Currency  ///////////////////////////////////
    public function Currency(Request $Request)
    {
        if ($this->CheckPermission('CurrencyAdmin') == true)
        {
            if ($Request->isMethod('post'))
            {
                $editCurrency = Currencie::find($Request->input('currency_id'));
                $editCurrency -> dollar_selling = $Request->input('dollar_selling');
                $editCurrency -> dollar_buy = $Request->input('dollar_buy');
                $editCurrency -> save();
            }
            else
            {
                $Currencies = Currencie::all();
                $arr = array('Currencies' => $Currencies);
                return view('admin/financial/Currency',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ChangeStatusCurrency(Request $Request)
    {
        if ($this->CheckPermission('CurrencyAdmin') == true)
        {
            $editCurrency = Currencie::find($Request->CurrencyID);
            if ($editCurrency->status == 1) {
                $editCurrency -> status = 0;
                $editCurrency -> save();
                return response()->json('off');
            } else {
                $editCurrency -> status = 1;
                $editCurrency -> save();
                return response()->json('success');
            }

        }
        else
        {
            return response()->json('Access Denied');
        }
    }

//////////////////////////////   Receipts Books  ///////////////////////////////////
    public function ReceiptsBooks()
    {
        if ($this->CheckPermission('ReceiptsBooksArchive') == true)
        {
            $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->whereIn('status',[1,2])->get();
            $arr = array('ReceiptsBooks' => $ReceiptsBooks);
            return view('admin/financial/ReceiptsBooks',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function ArchivedReceiptsBooks(Request $Request)
    {
        if ($this->CheckPermission('ReceiptsBooksArchive') == true)
        {
            $ReceiptBook = Receipts_book::find($Request->ReceiptID);
            if ($ReceiptBook->status == 2) {
                $ReceiptBook -> status = 1;
                $ReceiptBook -> employee_archive_id  = Auth::user()->employee->id;
                $ReceiptBook -> save();
                $arr = array('ReceiptsBooks' => $this->DataTableReceiptBooks() , 'Status' => 'ok');
            } elseif($ReceiptBook->status == 1) {
                $ReceiptBook -> status = 2;
                $ReceiptBook -> employee_archive_id  = null;
                $ReceiptBook -> save();
                $arr = array('ReceiptsBooks' => $this->DataTableReceiptBooks() , 'Status' => 'not ok');
            }

            return response()->json($arr);
        }
        else
        {
            return response()->json('Access Denied');
        }
    }

    public function DataTableReceiptBooks()
    {
        $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->whereIn('status',[1,2])->get();

            $response = array();
            foreach($ReceiptsBooks as $ReceiptsBook){

                if(!empty($ReceiptsBook->employee_archive_id)) {
                    $employee_archived = $ReceiptsBook->employee_archive->first_name.' '.$ReceiptsBook->employee_archive->last_name;
                } else {$employee_archived = null;}

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
                                "employee_archived"=>$employee_archived,
                                "controller"=>[
                                    "id_receipt"=>$ReceiptsBook->id,
                                    "status_receipt"=>$ReceiptsBook->status
                                ]);
            }

        return response()->json($response);
    }

}
