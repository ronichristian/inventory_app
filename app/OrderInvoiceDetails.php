<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceDetails extends Model
{
    public function OrderInvoice()
    {
        return $this->belongsTo('App\OrderInvoice');
    }

    public function Products()
    {
        return $this->hasMany('App\Product');
    }
}
