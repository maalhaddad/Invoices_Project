<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "invoice_number",
        "invoice_Date",
        "Due_date",
        "section_id",
        "product",
        "Amount_collection",
        "Amount_Commission",
        "Discount",
        "Rate_VAT",
        "Value_VAT",
        "Total",
        "note",
        'Payment_Date',
        'Value_Status',
        "Status",

    ];



    public function Section(){

        return $this->belongsTo(Sections::class);
    }

    public function Details(){

        return $this->hasMany(InvoicesDetails::class,'invoice_id');
    }

    public function Attachments(){

        return $this->hasMany(InvoicesAttachments::class,'invoice_id');
    }
}
