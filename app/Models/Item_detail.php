<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_detail extends Model
{
    use HasFactory;
    public $table="item_details";

    public function item()
    {
        return $this->belongsTo('App\Models\Item','item_id','id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Countrie','origin_country','id');
    }

}
