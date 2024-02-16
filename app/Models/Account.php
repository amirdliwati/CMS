<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $table="accounts";

    public function parent()
    {
        return $this->belongsTo('App\Models\Account','parent','id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Account','parent','id');
    }

    public function closing_account()
    {
        return $this->belongsTo('App\Models\Account','closing','id');
    }

    public function receipts_books()
    {
        return $this->hasMany('App\Models\Receipts_book','account_id','id');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employee','account_id','id');
    }
}
