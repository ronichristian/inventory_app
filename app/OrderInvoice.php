<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    public function OrderInvoiceDetails()
    {
        return $this->hasMany('App\OrderInvoiceDetails');
    }
}
