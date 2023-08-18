<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceDetail;

class Invoice extends Model
{
    use HasFactory;

    protected $table='invoices';

    public function InvoiceDetails(){
            return $this->hasOne(InvoiceDetail::class, 'invoice_id');
    }
}
