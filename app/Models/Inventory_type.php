<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_type extends Model
{
	public $table="inventory_types";

	public function sub_inventories()
    {
        return $this->hasMany('App\Models\Sub_inventorie','inventory_type_id','id');
    }
}
