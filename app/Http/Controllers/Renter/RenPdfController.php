<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\House;
use App\User;
use App\Booking;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RenPdfController extends Controller
{
    public function generate_pdf($book_id)
    {
        $book = Booking::findOrFail($book_id);
        $house = House::findOrFail($book->house_id);
        $users = User::where('id', Auth::id())->latest()->get();
        $landlord = User::findOrFail($book->landlord_id);
        
        $data = 'Thank you for using our website.';

        $order = Order::where('house_id', $house->id)->where('user_id', Auth::id())->first();

        // dd($order);


        $pdf = Pdf::loadView('renter/booking/billing_invoice',compact('data','house','users','order','book','landlord'));
        return $pdf->stream('billing-invoice');
    }
}
