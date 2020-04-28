<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function SalesInvoice()
    {
        return $this->belongsTo('App\SalesInvoiceDetail');
    }

    public function OrderInvoiceDetails()
    {
        return $this->belongsTo('App\OrderInvoiceDetails');
    }

    public function Category()
    {
        return $this->belongsTo('App\Category');
    }
}
