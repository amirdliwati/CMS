<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    public $table="categories";

    public function parent()
    {
        return $this->belongsTo('App\Models\Categorie','parent','id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Categorie','parent','id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item','category_id','id');
    }

}
