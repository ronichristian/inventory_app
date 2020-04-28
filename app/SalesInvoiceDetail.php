<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceDetail extends Model
{
    public function sales_invoice()
    {
        return $this->belongsTo('App\SalesInvoice');
    }
}
