<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'created_by'
    ];


    public function Products(){

        return $this->hasMany(Product::class,'section_id');
    }

    public function Invoice(){

        return $this->hasMany(Invoices::class);
    }

    // public function InvoiceDetails(){

    //     return $this->hasMany(InvoicesDetails::class);
    // }
}
