<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesAttachments extends Model
{
    use HasFactory;

    protected $fillable = [

        'file_name',
        'invoice_number',
        'invoice_id',
        'Created_by'
    ];


    public function Invoice(){

        return $this->belongsTo(Invoices::class,'invoice_id');
    }

    public function updatefilename($newfile){

        
    }
}
