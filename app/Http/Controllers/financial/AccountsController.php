<?php

namespace App\Http\Controllers\financial;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Account,Employee};

class AccountsController extends Controller
{
//////////////////////////////   Management Accounts  ///////////////////////////////////
    public function View()
    {
        if ($this->CheckPermission('ManageAccountsFinancial') == true)
        {
            $Accounts = Account::all();
            $Employees = Employee::all();
            $arr = array('Accounts' => $Accounts, 'Employees' => $Employees);
            return view('financial/accounts/ManagementTreeAccounts',$arr);
        }
        else
        {
            return view('public/AccessDenied');
        }
    }

    public function AddAccount(Request $Request)
    {
        if (empty(Account::where('account_number',$Request->input('account_number'))->first())) {
            $newAccount = new Account();
            $newAccount -> account_number = $Request->input('account_number');
            $newAccount -> name = $Request->input('name');
            if ($Request->input('parent') != 0) { $newAccount -> parent = $Request->input('parent'); }
            $newAccount -> closing = $Request->input('closing');
            $newAccount -> type = $Request->input('type');
            $newAccount -> notes = $Request->input('notes');
            $newAccount -> save();

            $this->AddToLog('Added Account',$newAccount->name , $newAccount->id);

            return response()->json(Account::all());
        }else {
            $MSG = 'Number Error';
            return response()->json($MSG);
        }

    }

    public function AddAccountByTree(Request $Request)
    {
        if (empty(Account::where('account_number',$Request->input('account_number'))->first())) {
            $newAccount = new Account();
            $newAccount -> account_number = $Request->input('number_account');
            $newAccount -> name = $Request->input('name_account');
            $newAccount -> parent = $Request->input('parent_id');
            $newAccount -> closing = $Request->input('closing_account');
            $newAccount -> type = $Request->input('type_account');
            $newAccount -> notes = $Request->input('notes_account');
            $newAccount -> save();

            $this->AddToLog('Added Account',$newAccount->name , $newAccount->id);

            return response()->json(Account::all());
        }else {
            $MSG = 'Number Error';
            return response()->json($MSG);
        }

    }

    public function ManageAccounts()
    {
        if ($this->CheckPermission('ManageAccountsFinancial') == true)
        {
            $Accounts = Account::all();
            $Employees = Employee::all();
            $arr = array('Accounts' => $Accounts, 'Employees' => $Employees);
            return view('financial/accounts/ManageAccounts',$arr);
        }
        else
        {
            return view('public/AccessDenied');
        }
    }

    public function ChangeAccount(Request $Request)
    {
        if ($Request->input('parameter_type') == 'Company')
        {
            $changeAccount = Companie::find($Request->input('parameter_id'));
            $changeAccount -> account_id = $Request->input('account_id');
            $changeAccount -> save();
            $this->AddToLog('Modify Account',$changeAccount->name , $Request->input('account_id'));
        }
        elseif ($Request->input('parameter_type') == 'Supplier')
        {
            $changeAccount = Supplier::find($Request->input('parameter_id'));
            $changeAccount -> account_id = $Request->input('account_id');
            $changeAccount -> save();
            $this->AddToLog('Modify Account',$changeAccount->name , $Request->input('account_id'));
        }

        elseif ($Request->input('parameter_type') == 'Employee')
        {
            $changeAccount = Employee::find($Request->input('parameter_id'));
            $changeAccount -> account_id = $Request->input('account_id');
            $changeAccount -> save();
            $this->AddToLog('Modify Account',$changeAccount->first_name . ' ' . $changeAccount->last_name , $Request->input('account_id'));
        }

        return response()->json('OK');
    }

//////////////////////////////   Modify Accounts  ///////////////////////////////////
    public function ModifyAccountsTree()
    {
        if ($this->CheckPermission('ModifyAccountsFinancial') == true)
        {
            $Accounts = Account::all();
            $arr = array('Accounts' => $Accounts);
            return view('financial/accounts/ModifyTreeAccounts',$arr);
        }
        else
        {
            return view('public/AccessDenied');
        }

    }

    public function DeleteAccountCard(Request $Request)
    {
        $category = Account::find($Request->IDAccountCard);
        if ($category->type == 0) {
            if ($category->children->count() == 0)
            {
                $DeleteAccountCard = Account::find($Request->IDAccountCard);
                $DeleteAccountCard -> delete();

                $this->AddToLog('Deleted Category',$DeleteAccountCard->name , $DeleteAccountCard->id);

                return response()->json(Account::all());
            }
            else
            {
                $MSG = 'Delete Error';
                return response()->json($MSG);
            }
        }
        else {
            if ($category->items->count() == 0)
            {
                $DeleteAccountCard = Account::find($Request->IDAccountCard);
                $DeleteAccountCard -> delete();

                $this->AddToLog('Deleted Category',$DeleteAccountCard->name , $DeleteAccountCard->id);

                return response()->json(Account::all());
            }
            else
            {
                $MSG = 'Delete Error';
                return response()->json($MSG);
            }
        }
    }

    public function RenameAccountCard(Request $Request)
    {
        if ($Request->input('rename_account_id') != '')
        {
            if ( Account::where('account_number',$Request->input('account_number'))->first() != '' &&  Account::where('name',$Request->input('name'))->first() != '') {

                $MSG = 'Number Error';
                return response()->json($MSG);
            }
            elseif ( Account::where('account_number',$Request->input('account_number'))->first() != '' &&  Account::where('name',$Request->input('name'))->first() == '') {

                $MSG = 'Number Error';
                return response()->json($MSG);
            }
            else {
                $RenameAccountCard = Account::find($Request->input('rename_account_id'));
                $RenameAccountCard -> account_number = $Request->input('account_number');
                $RenameAccountCard -> name = $Request->input('name');
                $RenameAccountCard -> save();
                $this->AddToLog('Rename Account Card',$RenameAccountCard->name , $RenameAccountCard->id);
                return response()->json(Account::all());
            }

        }
        else
        {
            $MSG = 'Rename Error';
            return response()->json($MSG);
        }
    }

    public function ChangeParentAccountCard(Request $Request)
    {
        if ($Request->input('change_parent_account_id') != '')
        {
            $changeParentAccountCard = Account::find($Request->input('change_parent_account_id'));
            if ($changeParentAccountCard->id != $Request->input('parent')) {
                if ($Request->input('parent') == 0) {
                    $changeParentAccountCard -> parent = NULL;
                } else {
                    $changeParentAccountCard -> parent = $Request->input('parent');
                }
                $changeParentAccountCard -> save();
                $this->AddToLog('Change Parent Account Card',$changeParentAccountCard->name , $changeParentAccountCard->id);
                return response()->json(Account::all());
            }
            else
            {
                $MSG = 'Error';
                return response()->json($MSG);
            }
        }
        else
        {
            $MSG = 'Error';
            return response()->json($MSG);
        }
    }

    public function ChangeClosingAccountCard(Request $Request)
    {
        if ($Request->input('change_closing_account_id') != '')
        {
            $ClosingAccountCard = Account::find($Request->input('change_closing_account_id'));
            $ClosingAccountCard -> closing = $Request->input('closing');
            $ClosingAccountCard -> save();
            return response()->json(Account::all());
        }
        else
        {
            $MSG = 'Modify Error';
            return response()->json($MSG);
        }
    }

}
