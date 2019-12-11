<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_date', 'customer_id'];
    public function instruments() {
        return $this->hasMany('App\Instrument');
    }

    public function customer() {
        return $this->belongsTo('App\Customer');
    }
}
