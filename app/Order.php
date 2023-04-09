<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\House;

class Order extends Model
{
    public function house(){
        return $this->belongsTo(House::class);
    }
    
    protected $fillable = [
        'user_id', 'house_id', 'amount', 'status', 'transaction_id', 'currency'
    ];
}
