<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\House;
use App\Order;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;  

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $houses = House::with('order')->latest()->paginate(8);
        $housecount = House::all()->count();
        $orders = Order::with('house')->latest()->get();
        // dd( $houses);
        return view('admin.house.index', compact('houses', 'housecount', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function switch($id)
    {
        $house = House::find($id);

        if($house->a_status == 0){
            $house->a_status = 1;
            $house->status = 1;
        }else{
            $house->a_status = 0;
            $house->status = 0;
        }
        $house->save();

        session()->flash('success', 'House Status Changed Successfully');
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        return view('admin.house.show')->with('house', $house); 
    }

    public function manageLandlord(){
        $landlords = User::where('role_id',2)->paginate(10);
        return view('admin.manageLandlord.index', compact('landlords'));
    }

    public function removeLandlord($id){
        $user = User::findOrFail($id);

        if($user->houses->count() > 0){
            session()->flash('danger', 'You do not remove landlord right now. Because he have some houses. At first 
            you have to remove houses, then remove him');
            return redirect()->back();
        }

        House::where('user_id', $user->id)->delete();

        $user->delete();

        session()->flash('success', 'Landlord Removed Successfully');
        return redirect()->back();

    }


    public function manageRenter(){
        $renters = User::where('role_id',3)->paginate(10);
        return view('admin.manageRenter.index', compact('renters'));
    }

    public function removeRenter($id){

        if(Booking::where('renter_id', $id)->where('booking_status', "booked")->count() > 0){
            session()->flash('danger', 'You do not able to remove this renter. Because he/she have already booked houses from your website');
            return redirect()->back();
        }


        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('success', 'Renter Removed Successfully');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        //delete multiple added images
        foreach(json_decode($house->images) as $picture){
            @unlink("images/". $picture);
        }

        //delete old featured image
        if(Storage::disk('public')->exists('featured_house/'.$house->featured_image)){
            Storage::disk('public')->delete('featured_house/'.$house->featured_image);
        }

        $house->delete();
        return redirect(route('admin.house.index'))->with('success', 'House Removed Successfully');
    }
}
