<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf_template extends Model
{
    use HasFactory;
    public $table="pdf_templates";

    public function font_type()
    {
        return $this->belongsTo('App\Models\Font_type','font_type_id','id');
    }

    public function template_logos()
    {
        return $this->hasMany('App\Models\Template_logo','pdf_template_id','id');
    }

    public function payrolls()
    {
        return $this->hasMany('App\Models\Payroll','pdf_template_id','id');
    }

    public function receipts_books()
    {
        return $this->hasMany('App\Models\Receipts_book','pdf_template_id','id');
    }

}
