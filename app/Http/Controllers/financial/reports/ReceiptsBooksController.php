<?php

namespace App\Http\Controllers\financial\reports;

use Controller, Auth, Carbon;
use Illuminate\Http\Request;
use App\Models\{Receipts_book};

class ReceiptsBooksController extends Controller
{
/////////////////////////////////////  Management Receipts Books  ///////////////////////////////////////////////////////
    public function ReceiptsBooks()
    {
        if ($this->CheckPermission('ReceiptsBooksReport') == true)
        {
            $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->whereIn('status',[1,2])->get();
            $arr = array('ReceiptsBooks' => $ReceiptsBooks);
            return view('financial/reports/ReceiptsBooks',$arr);
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
                $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->whereIn('status',[1,2])->get();
                $arr = array('ReceiptsBooks' => $this->DataTableReceiptBooks($ReceiptsBooks) , 'Status' => 'ok');
            } elseif($ReceiptBook->status == 1) {
                $ReceiptBook -> status = 2;
                $ReceiptBook -> employee_archive_id  = null;
                $ReceiptBook -> save();
                $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::now()->year)->whereMonth('date',Carbon::now()->format('m'))->whereIn('status',[1,2])->get();
                $arr = array('ReceiptsBooks' => $this->DataTableReceiptBooks($ReceiptsBooks) , 'Status' => 'not ok');
            }

            return response()->json($arr);
        }
        else
        {
            return response()->json('Access Denied');
        }
    }

    public function DataTableReceiptBooks($ReceiptsBooks)
    {
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
                            "type"=>$ReceiptsBook->type);
        }

        return $response;
    }

/////////////////////////////////////  Search Receipts Books  ///////////////////////////////////////////////////////
    public function SearchReceiptBooksByMonth(Request $Request)
    {
        if ($this->CheckPermission('ReceiptsBooksReport') == true)
        {
            $ReceiptsBooks = Receipts_book::whereYear('date',Carbon::parse($Request->Date)->format('Y'))->whereMonth('date',Carbon::parse($Request->Date)->format('m'))->get();

            return response()->json($this->DataTableReceiptBooksSearch($ReceiptsBooks));
        }
        else
        {
            return response()->json('Access Denied');
        }
    }

    public function GetReceiptsBooks(Request $Request)
    {
        if ($this->CheckPermission('ReceiptsBooksReport') == true)
        {
            if ($Request->Type == 4) {
                $ReceiptsBooks = Receipts_book::all();
            } else {
                $ReceiptsBooks = Receipts_book::where('type',$Request->Type)->get();
            }

            return response()->json($this->DataTableReceiptBooksSearch($ReceiptsBooks));
        }
        else
        {
            return response()->json('Access Denied');
        }
    }

    public function DataTableReceiptBooksSearch($ReceiptsBooks)
    {
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

        return $response;
    }

}
