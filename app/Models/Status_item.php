<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status_item extends Model
{
    public $table="status_items";

    public function items()
    {
        return $this->hasMany('App\Models\Item','status_id','id');          
    }

}
