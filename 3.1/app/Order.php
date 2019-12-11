<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function instruments() {
        return $this->belongsToMany('App\Instrument', 'orders_instruments');
    }
}
