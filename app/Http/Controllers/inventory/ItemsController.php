<?php

namespace App\Http\Controllers\inventory;

use Controller, Auth, Storage, DNS1D, Image;
use Illuminate\Http\Request;
use App\Models\{Item,Item_detail,Categorie,Branch,Countrie,Sub_inventorie};

class ItemsController extends Controller
{
/////////////////////////////////////  Management Items  //////////////////////////////////////////////////////
    public function AddItem(Request $Request)
    {
        if ($this->CheckPermission('ManagementItems') == true)
        {
            if ($Request->isMethod('POST'))
            {
                for ($i=0; $i < $Request->input('qt'); $i++) {
                    $newItem = new Item();
                    $newItem -> category_id = $Request->input('category_id');
                    $newItem -> brand = $Request->input('brand');
                    $newItem -> model = $Request->input('model');
                    $newItem -> quality = $Request->input('quality');
                    $newItem -> status_id = 2;
                    $newItem -> serial = $Request->input('serial');
                    $newItem -> description = $Request->input('description');
                    $newItem -> sub_inventory_id = $Request->input('sub_inventory_id');
                    $newItem -> barcode = $Request->input('barcode');
                    $newItem -> save();

                    $newItemDetails = new Item_detail();
                    $newItemDetails -> item_id  = $newItem->id;
                    $newItemDetails -> origin_country = $Request->input('origin_country');
                    $newItemDetails -> manufacture = $Request->input('manufacture');
                    $newItemDetails -> date_of_manufacture = $Request->input('date_of_manufacture');
                    $newItemDetails -> color = $Request->input('color');
                    $newItemDetails -> expire_date = $Request->input('expire_date');
                    $newItemDetails -> save();

                    if ($Request->hasFile('image_path'))
                    {
                        $file = $Request->file('image_path');
                        $filename ='Image_Item_'. $newItem->id . '.' . $file->guessClientExtension();
                        Storage::disk('local')->putFileAs("/Items_Images/".$newItem->id, $file, $filename);
                        $newItemDetails -> image_path = 'Items_Images/' . $newItem->id . '/' . $filename;
                        $newItemDetails -> save();
                    }
                }

                $Request->session()->put('msgSuccess', 'The Item has been added successfully');
            } else{
                $Categories = Categorie::where('type',1)->get();
                $SubInventories = Sub_inventorie::all();
                $Countries = Countrie::all();
                $arr = array('Categories' => $Categories , 'SubInventories' => $SubInventories , 'Countries' => $Countries);
                return view('inventory/items/AddItem',$arr);
            }
        }
        else
        {
            return view('errors/AccessDenied');
        }
    }

    public function Items(Request $Request)
    {
        if ($this->CheckPermission('ViewItemsInventory') == true)
        {
            $Items = Item::where('status_id',2)->get();
            $Categories = Categorie::all();
            $arr = array('Items' => $Items , 'Categories' => $Categories);
            return view('inventory/items/Items',$arr);
        }
        else
        {
            return view('errors/AccessDenied');
        }

    }

    public function ItemDetails(Request $Request)
    {
        if ($this->CheckPermission('ViewItemsInventory') == true || $this->CheckPermission('ManagementItems') == true)
        {
            if ($Request->isMethod('GET'))
            {
                $ItemDetails = Item_detail::where('item_id',$Request->IdItem)->first();
                $Categories = Categorie::where('type',2)->get();
                $SubInventories = Sub_inventorie::all();
                $Countries = Countrie::all();
                $arr = array('ItemDetails' => $ItemDetails , 'Categories' => $Categories , 'SubInventories' => $SubInventories , 'Countries' => $Countries);
                return view('inventory/items/ItemDetails',$arr);
            }
            else
            {
                $ItemDetails =  Item_detail::find($Request->input('IDItemDetails'));
                $ItemDetails -> origin_country = $Request->input('origin_country');
                $ItemDetails -> manufacture = $Request->input('manufacture');
                $ItemDetails -> date_of_manufacture = $Request->input('date_of_manufacture');
                $ItemDetails -> color = $Request->input('color');
                $ItemDetails -> expire_date = $Request->input('expire_date');
                $ItemDetails -> save();

                $editItem =  Item::find($ItemDetails->item->id);
                $editItem -> category_id = $Request->input('category_id');
                $editItem -> brand = $Request->input('brand');
                $editItem -> model = $Request->input('model');
                $editItem -> quality = $Request->input('quality');
                $editItem -> serial = $Request->input('serial');
                $editItem -> description = $Request->input('description');
                $editItem -> sub_inventory_id = $Request->input('sub_inventory_id');
                $editItem -> barcode = $Request->input('barcode');
                $editItem -> save();

                if ($Request->hasFile('image_path'))
                {
                    $file = $Request->file('image_path');
                    $filename ='Image_Item_'. $ItemDetails->item->id . '.' . $file->guessClientExtension();
                    Storage::disk('local')->putFileAs("/Items_Images/".$ItemDetails->item->id, $file, $filename);
                    $ItemDetails -> image_path = 'Items_Images/' . $ItemDetails->item->id . '/' . $filename;
                    $ItemDetails -> save();
                }

                $this->AddToLog('Edited Item Details',$ItemDetails->item->serial , $ItemDetails->item->id);
                $Request->session()->put('msgWarning', 'The Item has been edited successfully');

            }

        }
        else
        {
            return view('errors/AccessDenied');
        }

    }

    public function PreviewImageItem(Request $Request)
    {
        $Item =  Item::find($Request->IdItem);
        $img = Image::make(Storage::get($Item->item_details->image_path));
        $img->save('uploads/Temp/Image-Item-' . $Item->id . '.jpg');
    }

    public function DeleteItem(Request $Request)
    {
        if ($this->CheckPermission('ManagementItems') == true)
        {
            $DeleteItem = Item::find($Request->ItemID);
            Storage::disk('local')->deleteDirectory('Items_Images/'.$DeleteItem->id);
            $DeleteItem -> delete();
            $this->AddToLog('Deleted Item',$DeleteItem->serial , $DeleteItem->id);
            return response()->json('Success');
        }
        else
        {
            $MSG = 'Access Denied';
            return response()->json($MSG);
        }
    }

/////////////////////////////////////  Filter Items  //////////////////////////////////////////////////////
    public function GetItemsList(Request $Request)
    {
        $Items = Item::where('category_id',$Request->ItemID)->get();
        return response()->json($this->DataTableItems($Items));
    }

    public function GetItemsListByStatus(Request $Request)
    {
        $Items = Item::where('status_id',$Request->Status)->get();
        return response()->json($this->DataTableItems($Items));
    }

    public function DataTableItems($Items)
    {
        $response = array();
            foreach($Items as $Item){
            $response[] = array("id"=>$Item->id,
                                "serial_number" =>[
                                    "id"=>$Item->id,
                                    "serial"=>$Item->serial],
                                "name"=>$Item->name_item->name,
                                "model"=>$Item->model,
                                "quality"=>$Item->quality,
                                "status" =>[
                                    "id"=>$Item->status_id,
                                    "status_name"=>$Item->status_item->name],
                                "inventory"=>$Item->sub_inventory->branch->name . '->' . $Item->sub_inventory->name);
            }

        return $response;
    }

    public function PrintItemBarcode(Request $Request)
    {
        $Item = Item::find($Request->ItemID);
        $Barcode = Image::make(DNS1D::getBarcodePNG($Item->barcode, 'C128',1,44,array(0,0,0),true));
        $Barcode->save('uploads/Temp/qr-item-'.$Item->id.'.png');
        return response()->json("ok");
    }

}
