<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipts_book extends Model
{
    use HasFactory;
    public $table="receipts_books";

    public function currencie()
    {
        return $this->belongsTo('App\Models\Currencie','currency_id','id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function employee_archive()
    {
        return $this->belongsTo('App\Models\Employee','employee_archive_id','id');
    }

    public function employee_cancel()
    {
        return $this->belongsTo('App\Models\Employee','employee_cancel_id','id');
    }

    public function pdf_template()
    {
        return $this->belongsTo('App\Models\Pdf_template','pdf_template_id','id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account','account_id','id');
    }

}
