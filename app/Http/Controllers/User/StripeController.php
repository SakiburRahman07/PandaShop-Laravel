<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; 
use App\Models\Cartbox;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\OrderComplete;
use Illuminate\Support\Facades\Notification;

class StripeController extends Controller
{
    public function StripeOrder(Request $request){
        $user_id = Auth::id();

        $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->price * $cart->quantity;
        }

        if(session()->has('coupon')){
            $total_amount = session()->get('coupon')['total_amount'];
        }else{
            $total_amount = round($totalPrice);
        }

        \Stripe\Stripe::setApiKey('sk_test_51P1iVXRsP4yiTcaH1Nfuaqezi3wBSEy0yJ6ghvXT9AJcsDzr8RP5e5JtMjlaiLg030vVAsh2KbxcdTMHlmSesGBL00bxgSpQd2');

 
        $token = $_POST['stripeToken'];

        $charge = \Stripe\Charge::create([
          'amount' => $total_amount*100,
          'currency' => 'usd',
          'description' => 'PANDASHOP',
          'source' => $token,
          'metadata' => ['order_id' => uniqid()],
        ]);

        //dd($charge);

        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adress' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,

            'payment_type' => $charge->payment_method,
            'payment_method' => 'Stripe',
            'transaction_id' => $charge->balance_transaction,
            'currency' => $charge->currency,
            'amount' => $total_amount,
            'order_number' => $charge->metadata->order_id,

            'invoice_no' => 'SAKIB'.mt_rand(10000000,99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'), 
            'status' => 'pending',
            'created_at' => Carbon::now(),  

        ]);

        // Start Send Email

        $invoice = Order::findOrFail($order_id);

        $data = [

            'invoice_no' => $invoice->invoice_no,
            'amount' => $total_amount,
            'name' => $invoice->name,
            'email' => $invoice->email,

        ];

        Mail::to($request->email)->send(new OrderMail($data));

        // End Send Email

        $carts = Cartbox::with('product')->where('user_id', Auth::id())->latest()->get();
        foreach($carts as $cart){
            
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->product_id,
                'vendor_id' => $cart->vendor_id,
                'color' => $cart->color,
                'size' => $cart->size,
                'qty' => $cart->quantity,
                'price' => $cart->price,
                'created_at' =>Carbon::now(),

            ]);

        } // End Foreach



        if (session()->has('coupon')) {
           session()->forget('coupon');
        }

      //  Cart::destroy();
        $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

      
        foreach ($carts as $cart) {
            $cart->delete();
        }

        $notification = array(
            'message' => 'Your Order Place Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('dashboard')->with($notification); 



    }// End Method 

    public function CashOrder(Request $request){
        $user_id = Auth::id();

        $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->price * $cart->quantity;
        }


        $user = User::where('role','admin')->get();


        if(session()->has('coupon')){
            $total_amount = session()->get('coupon')['total_amount'];
        }else{
            $total_amount = round($totalPrice);
        }

        
        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adress' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,

            'payment_type' => 'Cash On Delivery',
            'payment_method' => 'Cash On Delivery',
            
            'currency' => 'Usd',
            'amount' => $total_amount,
            

            'invoice_no' => 'SAKIB'.mt_rand(10000000,99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'), 
            'status' => 'pending',
            'created_at' => Carbon::now(),  

        ]);

         // Start Send Email

         $invoice = Order::findOrFail($order_id);

         $data = [
 
             'invoice_no' => $invoice->invoice_no,
             'amount' => $total_amount,
             'name' => $invoice->name,
             'email' => $invoice->email,
 
         ];
 
         Mail::to($request->email)->send(new OrderMail($data));
 
         // End Send Email

        
        $carts = Cartbox::with('product')->where('user_id', Auth::id())->latest()->get();
        foreach($carts as $cart){
            
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->product_id,
                'vendor_id' => $cart->vendor_id,
                'color' => $cart->color,
                'size' => $cart->size,
                'qty' => $cart->quantity,
                'price' => $cart->price,
                'created_at' =>Carbon::now(),

            ]);

        } // End Foreach

        if (session()->has('coupon')) {
           session()->forget('coupon');
        }

      //  Cart::destroy();

      $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

      
      foreach ($carts as $cart) {
          $cart->delete();
      }

        $notification = array(
            'message' => 'Your Order Place Successfully',
            'alert-type' => 'success'
        );
        Notification::send($user, new OrderComplete($request->name));
        return redirect()->route('dashboard')->with($notification); 



    }// End Method 

}
