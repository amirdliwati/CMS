<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencie extends Model
{
    public $table="currencies";

    public function employees()
    {
        return $this->hasMany('App\Models\Employee','currencies_id','id');
    }

    public function receipts_books()
    {
        return $this->hasMany('App\Models\Receipts_book','currency_id','id');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer','currency_id','id');
    }

}
