<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order2 extends Model
{
    // public function house(){
    //     return $this->belongsTo(House::class);
    // }
    public function booking(){
        return $this->belongsTo(Booking::class);
    }
    
    protected $fillable = [
        'user_id', 'house_id', 'amount', 'status', 'transaction_id', 'currency'
    ];
}
