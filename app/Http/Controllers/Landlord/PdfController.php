<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\House;
use App\User;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generate_pdf($house_id)
    {
        $houses = House::findOrFail($house_id);
        $users = User::where('id', Auth::id())->latest()->get();
        $data = 'Thank you for using our website.';

        $order = Order::where('house_id', $houses->id)->first();


        $pdf = Pdf::loadView('landlord/house/billing_invoice',compact('data','houses','users','order'));
        return $pdf->stream('billing-invoice');
    }
}
