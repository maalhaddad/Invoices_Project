<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        "invoice_number",
        "invoice_id",
        "section",
        "product",
        "note",
        'Value_Status',
        'Payment_Date',
        "Status",
        'user'

    ];

    // public function Section(){

    //     return $this->belongsTo(Sections::class);
    // }

    public function Invoice(){

       return  $this->belongsTo(Invoices::class,'invoice_id');
    }
}
