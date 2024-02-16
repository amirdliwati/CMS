<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    public $table="items";
    public function status_item()
    {
        return $this->belongsTo('App\Models\Status_item','status_id','id');
    }

    public function name_item()
    {
        return $this->belongsTo('App\Models\Categorie','category_id','id');
    }

    public function item_details()
    {
        return $this->hasOne('App\Models\Item_detail','item_id','id');
    }

    public function sub_inventory()
    {
        return $this->belongsTo('App\Models\Sub_inventorie','sub_inventory_id','id');
    }

}
