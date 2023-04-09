<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wishlist;
use App\House;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    
    public function index()
    {
        $wishlist = Wishlist::all();
        return view('renter.wishlist',compact('wishlist'));
    }

    public function addWsihlist($house_id){
        
        $wishlist = Wishlist::where('house_id', $house_id)->where('user_id', Auth::user()->id)->first();

        if($wishlist){
            return redirect()->back()->with('info','Wishlist Already Exist');
        }
        
        Wishlist::create([
            'house_id' => $house_id,
            'user_id' => Auth::user()->id
        ]);
        return redirect()->back()->with('success','Wishlist Added Successfully');
    }

    public function cancelWishlist($id){
        Wishlist::find($id)->delete();
        return redirect()->back()->with('success','Wishlist Removed Successfully');;
    }
}
