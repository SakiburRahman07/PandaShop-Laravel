<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDistricts;
use App\Models\ShipState;
use App\Models\Cartbox;
use Illuminate\Support\Facades\Auth;
class CheckoutController extends Controller
{
    public function DistrictGetAjax($division_id){

        $ship = ShipDistricts::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
        return json_encode($ship);

    } // End Method 

    public function StateGetAjax($district_id){

        $ship = ShipState::where('district_id',$district_id)->orderBy('state_name','ASC')->get();
        return json_encode($ship);

    }// End Method 

    public function CheckoutStore(Request $request){
        $id = session('user_id');
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['post_code'] = $request->post_code;   

        $data['division_id'] = $request->division_id;
        $data['district_id'] = $request->district_id;
        $data['state_id'] = $request->state_id;
        $data['shipping_address'] = $request->shipping_address;
        $data['notes'] = $request->notes; 
        $carts = Cartbox::with('product')->where('user_id', $id)->latest()->get();
        $totalPrice = 0;
        
        foreach ($carts as $cart) {
            $totalPrice += $cart->price * $cart->quantity;
        }
        $cartTotal =  $totalPrice;

        if ($request->payment_option == 'stripe') {
           return view('frontend.payment.stripe',compact('data','cartTotal'));
        }elseif ($request->payment_option == 'card'){
            return 'Card Page';
        }else{
            return view('frontend.payment.cash',compact('data','cartTotal'));
        }


    }// End Method 
}
