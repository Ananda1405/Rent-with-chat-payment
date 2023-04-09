<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Library\SslCommerz\SslCommerzNotification;
use Auth;
use App\Order;
use App\Order2;

class SslPayment2Controller extends Controller
{

    

    public function checkout2(Request $request)              //----------------------
    {
        

        $house_rent = $request->rent;

        $post_data = array();
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['total_amount'] = $house_rent;


        # CUSTOMER INFORMATION
        $post_data['user_id'] = $request->user_id;

        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        $sslc = new SslCommerzNotification();
        
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

   


    public function success(Request $request)
    {

        // ============ My Code ==============

        //Data received in variable

        if(Auth::user()->role_id == 3){

            $user_properties = DB::table('bookings')
                        ->select('id','house_id')
                        ->where('renter_id',Auth::user()->id)
                        ->latest()
                        ->first();

                        $tran_id = $request->input('tran_id');
                        $amount = $request->input('amount');
                        $currency = $request->input('currency');
                
                        $order = new Order();
                        $order->user_id = Auth::user()->id;
                        $order->house_id = $user_properties->house_id;
                        $order->amount = $amount;
                        $order->status = 'Done';
                        $order->transaction_id = $tran_id;
                        $order->currency = $currency;
                        $order->save();

        }else{
            $user_properties = DB::table('houses')
                        ->select('id')
                        ->where('user_id',Auth::user()->id)
                        ->latest()
                        ->first();

            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency');

            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->house_id = $user_properties->id;
            $order->amount = $amount;
            $order->status = 'Done';
            $order->transaction_id = $tran_id;
            $order->currency = $currency;
            $order->save();
        }

        

        // $tran_id = $request->input('tran_id');
        // $amount = $request->input('amount');
        // $currency = $request->input('currency');

        // $order = new Order2();
        // $order->user_id = Auth::user()->id;
        // $order->house_id = $user_properties->id;
        // $order->amount = $amount;
        // $order->status = 'Done';
        // $order->transaction_id = $tran_id;
        // $order->currency = $currency;
        // $order->save();

        // ======================================


        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

            if ($validation == TRUE) {
               

                echo "<br >Transaction is successfully Completed";
            }
            
        }

        return view('renter/payment/success_payment');

    }


    public function fail2(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        return view('renter/payment/failed_payment');
    }

    public function cancel2(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }


    }

    public function ipn2(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency, $request->all());
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Failed']);

                    echo "validation Fail";
                }

            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}
