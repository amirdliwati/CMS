<?php

namespace App\Http\Controllers\inventory;

use Controller, Auth;
use Illuminate\Http\Request;
use App\Models\{Item,Categorie,Sub_inventorie,Branch,Inventory_type};

class ManagementInventoryController extends Controller
{
//////////////////////////////   Management Inventory  ///////////////////////////////////
    public function View(Request $Request)
    {
        if ($this->CheckPermission('ManagementInventory') == true)
        {
            $Categories = Categorie::all();
            $arr = array('Categories' => $Categories);
            return view('inventory/tree/ManagementInventory',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function AddCategory(Request $Request)
    {
        $newCategory = new Categorie();
        if ($Request->input('parent') != 0) {
            $newCategory -> parent = $Request->input('parent');
        }
        $newCategory -> name = $Request->input('name');
        if ($Request->input('type') == 'on') {
            $newCategory -> type = 0;
        } else{$newCategory -> type = 1;}
        $newCategory -> save();

        $this->AddToLog('Added Category',$newCategory->name , $newCategory->id);

        return response()->json(Categorie::all());
    }

    public function AddCategoryByTree(Request $Request)
    {
        $newCategory = new Categorie();
        $newCategory -> parent = $Request->input('parent_id');
        $newCategory -> name = $Request->input('name_category');
        if ($Request->input('type_category') == 'on') {
            $newCategory -> type = 0;
        } else{$newCategory -> type = 1;}
        $newCategory -> save();

        $this->AddToLog('Added Category',$newCategory->name , $newCategory->id);

        return response()->json(Categorie::all());
    }

    public function GetItemsList(Request $Request)
    {
        $Items = Item::where('category_id',$Request->ItemID)->get();
            $response = array();
            foreach($Items as $Item){
            $response[] = array("id"=>$Item->id,
                                "serial_number"=>$Item->serial,
                                "name"=>$Item->name_item->name,
                                "model"=>$Item->model,
                                "status" =>[
                                    "id"=>$Item->status_id,
                                    "status_name"=>$Item->status_item->name]);
            }

            return response()->json($response);
    }

//////////////////////////////   Modify Inventory  ///////////////////////////////////
    public function ModifyInventory(Request $Request)
    {
        if ($this->CheckPermission('ModifyInventory') == true)
        {
            $Categories = Categorie::all();
            $arr = array('Categories' => $Categories);
            return view('inventory/tree/ModifyInventory',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }

    }

    public function DeleteCategory(Request $Request)
    {
        $category = Categorie::find($Request->IDCategory);
        if ($category->type == 0) {
            if ($category->children->count() == 0)
            {
                $DeleteCategory = Categorie::find($Request->IDCategory);
                $DeleteCategory -> delete();

                $this->AddToLog('Deleted Category',$DeleteCategory->name , $DeleteCategory->id);

                return response()->json(Categorie::all());
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
                $DeleteCategory = Categorie::find($Request->IDCategory);
                $DeleteCategory -> delete();

                $this->AddToLog('Deleted Category',$DeleteCategory->name , $DeleteCategory->id);

                return response()->json(Categorie::all());
            }
            else
            {
                $MSG = 'Delete Error';
                return response()->json($MSG);
            }
        }
    }

    public function RenameCategory(Request $Request)
    {
        if ($Request->input('rename_category_id') != '')
        {
            $renameCategory = Categorie::find($Request->input('rename_category_id'));
            $renameCategory -> name = $Request->input('name');
            $renameCategory -> save();
            $this->AddToLog('Rename Category',$renameCategory->name , $renameCategory->id);
            return response()->json(Categorie::all());
        }
        else
        {
            $MSG = 'Rename Error';
            return response()->json($MSG);
        }
    }

    public function ChangeParentCategory(Request $Request)
    {
        if ($Request->input('change_parent_category_id') != '')
        {
            $changeParentCategory = Categorie::find($Request->input('change_parent_category_id'));
            if ($changeParentCategory->id != $Request->input('parent')) {
                if ($Request->input('parent') == 0) {
                    $changeParentCategory -> parent = NULL;
                } else {
                    $changeParentCategory -> parent = $Request->input('parent');
                }
                $changeParentCategory -> save();
                $this->AddToLog('Change Parent Category',$changeParentCategory->name , $changeParentCategory->id);
                return response()->json(Categorie::all());
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

//////////////////////////////   Management Sub Inventory  ///////////////////////////////////
    public function ManageSubInventory(Request $Request)
    {
        if ($this->CheckPermission('ManageSubInventory') == true)
        {
            if ($Request->isMethod('post'))
            {
                $newSubInventory = new Sub_inventorie();
                $newSubInventory -> name = $Request->input('name');
                $newSubInventory -> inventory_type_id = $Request->input('inventory_type_id');
                $newSubInventory -> branch_id = $Request->input('branch_id');
                $newSubInventory -> save();

                $this->AddToLog('Added Sub Inventory',$newSubInventory->name , $newSubInventory->id);
                $Request->session()->put('msgSuccess', 'The Sub Inventory has been added successfully');

            }
            else
            {
                $SubInventories = Sub_inventorie::all();
                $Branches = Branch::all();
                $InventoryTypes = Inventory_type::all();
                $arr = array('SubInventories' => $SubInventories , 'Branches' => $Branches , 'InventoryTypes' => $InventoryTypes);
                return view('inventory/other/SubInventory',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

}
