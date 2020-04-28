<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    public function SalesInvoiceDetail()
    {
        return $this->hasMany('App\SalesInvoiceDetail');
    }
}
