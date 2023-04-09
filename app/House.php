<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;

class House extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    protected $fillable = [
        'user_id','address','area_id','contact','number_of_room','number_of_toilet','number_of_belcony','description','month','rent','map_link','featured_image','images','video','status'
    ];
}
