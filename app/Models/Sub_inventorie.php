<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_inventorie extends Model
{
	public $table="sub_inventories";

	public function branch()
    {
        return $this->belongsTo('App\Models\Branch','branch_id','id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Inventory_type','inventory_type_id','id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item','sub_inventory_id','id');
    }
}
