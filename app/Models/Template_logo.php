<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template_logo extends Model
{
    use HasFactory;
    public $table="template_logos";

    public function pdf_template()
    {
        return $this->belongsTo('App\Models\Pdf_template','pdf_template_id','id');
    }

}
