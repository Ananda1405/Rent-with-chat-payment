<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Factories\HasFactory;



class Wishlist extends Model
{
    // use HasFactory;

    // public static function countWishlist($house_id) {
    //     $countWishlist = Wishlist::where(['user_id' => Auth::user()->id, 
    //     'house_id' => $house_id])->count();
    //     return $countWishlist;
    // }

    // protected $table = 'wishlists';

    protected $fillable = [
        'user_id', 'house_id',
    ];
}
