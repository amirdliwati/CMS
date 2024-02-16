<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Font_type extends Model
{
    use HasFactory;
    public $table="font_types";

    public function pdf_templates()
    {
        return $this->hasMany('App\Models\Pdf_template','font_type_id','id');
    }

}
