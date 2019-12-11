<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    protected $fillable = ['name', 'price', 'status', 'order_id'];
    public function order() {
        return $this->belongsTo('App\Order');
    }
}
