<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Cartbox;
use App\Models\Coupon;
//use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Session;
use App\Models\ShipDivision;


class CartController extends Controller
{

    public function AddToCart(Request $request, $id){

        if(session()->has('coupon')){
            session()->forget('coupon');
        }



        $product = Product::findOrFail($id);
        $id = session('user_id');
      //  if (Auth::check()) {
        if (session()->has('user_id')) {
            $exists = Cartbox::where('user_id',$id)->where('product_id',$id)->first();
      
                  if (!$exists) {

                    if ($product->discount_price == NULL) {

                        Cartbox::insert([
            
                            'product_id' => $id,
                            'user_id' => $id, 
                            'product_name' => $request->product_name,
                            'quantity' => $request->quantity,
                            'price' => $product->selling_price,
                            'image' => $product->product_thambnail,
                            'color' => $request->color,
                            'size' => $request->size,
                            'vendor_id' => $request->vendor_id,
                            
                        ]);
            
               return response()->json(['success' => 'Successfully Added on Your Cart' ]);
            
                    }else{
            
                        Cartbox::insert([
            
                            'product_id' => $id,
                            'user_id' => $id, 
                            'product_name' => $request->product_name,
                            'quantity' => $request->quantity,
                            'price' => $product->selling_price,
                            'image' => $product->product_thambnail,
                            'color' => $request->color,
                            'size' => $request->size,
                            'vendor_id' => $request->vendor_id,
                        ]);
            
               return response()->json(['success' => 'Successfully Added on Your Cart' ]);
            
                    }
                   
                    

                     return response()->json(['success' => 'Successfully Added On Your Cart' ]);
                  } else{
                      return response()->json(['error' => 'This Product Has Already on Your Cart' ]);
      
                  } 
      
              }else{
                  return response()->json(['error' => 'At First Login Your Account' ]);
              }



    }// End Method

    public function AddToCartDetails(Request $request, $id){
        if(session()->has('coupon')){
            session()->forget('coupon');
        }

        $product = Product::findOrFail($id);
        $id = session('user_id');

        if (session()->has('user_id')) {
            $exists = Cartbox::where('user_id',$id)->where('product_id',$id)->first();
      
                  if (!$exists) {

                    if ($product->discount_price == NULL) {

                        Cartbox::insert([
            
                            'product_id' => $id,
                            'user_id' => $id, 
                            'product_name' => $request->product_name,
                            'quantity' => $request->quantity,
                            'price' => $product->selling_price,
                            'image' => $product->product_thambnail,
                            'color' => $request->color,
                            'size' => $request->size,
                            'vendor_id' => $request->vendor,
                            
                        ]);
            
               return response()->json(['success' => 'Successfully Added on Your Cart' ]);
            
                    }else{
            
                        Cartbox::insert([
            
                            'product_id' => $id,
                            'user_id' => $id, 
                            'product_name' => $request->product_name,
                            'quantity' => $request->quantity,
                            'price' => $product->selling_price,
                            'image' => $product->product_thambnail,
                            'color' => $request->color,
                            'size' => $request->size,
                            'vendor_id' => $request->vendor,
                        ]);
            
               return response()->json(['success' => 'Successfully Added on Your Cart' ]);
            
                    }
                   
                    

                     return response()->json(['success' => 'Successfully Added On Your Cart' ]);
                  } else{
                      return response()->json(['error' => 'This Product Has Already on Your Cart' ]);
      
                  } 
      
              }else{
                  return response()->json(['error' => 'At First Login Your Account' ]);
              }



    }// End Method

    public function AddToCart2(Request $request, $product_id){
        $id = session('user_id');

        if (session()->has('user_id')) {
      $exists = Cart::where('user_id',$id)->where('product_id',$product_id)->first();

            if (!$exists) {
               Cart::insert([
                'user_id' => $id,
                'product_id' => $product_id,
                'created_at' => Carbon::now(),

               ]);
               return response()->json(['success' => 'Successfully Added On Your Cart' ]);
            } else{
                return response()->json(['error' => 'This Product Has Already on Your Cart' ]);

            } 

        }else{
            return response()->json(['error' => 'At First Login Your Account' ]);
        }

    } // End Method 

    public function AddMiniCart(){

            $id = session('user_id');


         $carts = Cartbox::with('product')->where('user_id',$id)->latest()->get();
         $cartQty = Cartbox::with('product')->where('user_id',$id)->count();
        // $cartTotal = Cartbox::total();

         $carts = Cartbox::with('product')->where('user_id', $id)->latest()->get();

        $totalPrice=0;
         foreach($carts as $cart){
            $totalPrice+= $cart->price * $cart->quantity;
       
        }
        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,  
            'cartTotal' => $totalPrice,
        

        ));
    }// End Method

    public function RemoveMiniCart($rowId){
        $id = session('user_id');
        Cartbox::where('user_id',$id)->where('id',$rowId)->delete();
       
        return response()->json(['success' => 'Product Remove From Cart']);

    }// End Method

    public function MyCart(){

        return view('frontend.mycart.view_mycart');

    }// End Method

    public function GetCartProduct(){

            $id = session('user_id');


        $carts = Cartbox::with('product')->where('user_id',$id)->latest()->get();
        $cartQty = Cartbox::with('product')->where('user_id',$id)->count();
       // $cartTotal = Cartbox::total();

        $carts = Cartbox::with('product')->where('user_id', $id)->latest()->get();

       $totalPrice=0;
        foreach($carts as $cart){
           $totalPrice+= $cart->price * $cart->quantity;
      
       }
       return response()->json(array(
           'carts' => $carts,
           'cartQty' => $cartQty,  
           'cartTotal' => $totalPrice,
       

       ));
   }// End Method

   public function CartRemove($rowId){
           $id = session('user_id');

    Cartbox::where('user_id',$id)->where('id',$rowId)->delete();
    $user_id = session('user_id');
    
    if (session()->has('coupon')) {
        $coupon_name = session('coupon')['coupon_name'];
        $coupon = Coupon::where('coupon_name', $coupon_name)->first();
        $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->price * $cart->quantity;
        }

        session()->put('coupon', [
            'coupon_name' => $coupon->coupon_name, 
            'coupon_discount' => $coupon->coupon_discount, 
            'discount_amount' => round($totalPrice * $coupon->coupon_discount/100), 
            'total_amount' => round($totalPrice - $totalPrice * $coupon->coupon_discount/100 )
        ]); 
    }
    return response()->json(['success' => 'Successfully Remove From Cart']);

}// End Method

public function CartDecrement($rowId) {
    // Check if the user is authenticated
    if (session()->has('user_id')) {
        $user_id = session('user_id');;
        $cartItem = Cartbox::where('user_id', $user_id)->where('id', $rowId)->first();
   
        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->update([
                    'quantity' => $cartItem->quantity - 1,
                ]);

                if (session()->has('coupon')) {
                    $coupon_name = session('coupon')['coupon_name'];
                    $coupon = Coupon::where('coupon_name', $coupon_name)->first();
                    $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

                    $totalPrice = 0;
                    foreach ($carts as $cart) {
                        $totalPrice += $cart->price * $cart->quantity;
                    }

                    session()->put('coupon', [
                        'coupon_name' => $coupon->coupon_name, 
                        'coupon_discount' => $coupon->coupon_discount, 
                        'discount_amount' => round($totalPrice * $coupon->coupon_discount/100), 
                        'total_amount' => round($totalPrice - $totalPrice * $coupon->coupon_discount/100 )
                    ]); 
                }
            } else {
                return response()->json('Quantity cannot be less than 1');
            }
        } else {
            return response()->json('Cart item not found');
        }
    } else {
        return response()->json('User not authenticated');
    }

    return response()->json('Decrement successful');
}



public function CartIncrement($rowId) {
    // Check if the user is authenticated
    if (session()->has('user_id')) {
        $user_id = session('user_id');;
        $cartItem = Cartbox::where('user_id', $user_id)->where('id', $rowId)->first();
   
        if ($cartItem) {
            if ($cartItem->quantity >= 1) {
                $cartItem->update([
                    'quantity' => $cartItem->quantity + 1,
                ]);

                if(session()->has('coupon')){
                    $coupon_name = session('coupon')['coupon_name'];
                    $coupon = Coupon::where('coupon_name', $coupon_name)->first();

                    $carts = Cartbox::with('product')->where('user_id', $user_id)->latest()->get();

                    $totalPrice = 0;
                    foreach ($carts as $cart) {
                        $totalPrice += $cart->price * $cart->quantity;
                    }

                    session()->put('coupon', [
                        'coupon_name' => $coupon->coupon_name, 
                        'coupon_discount' => $coupon->coupon_discount, 
                        'discount_amount' => round($totalPrice * $coupon->coupon_discount/100), 
                        'total_amount' => round($totalPrice - $totalPrice * $coupon->coupon_discount/100 )
                    ]); 
                }

            } else {
                return response()->json('Quantity cannot be less than 1');
            }
        } else {
            return response()->json('Cart item not found');
        }
    } else {
        return response()->json('User not authenticated');
    }

    return response()->json('Increment successful');
}


public function CouponApply(Request $request)
{
    $id = session('user_id');
    $coupon = Coupon::where('coupon_name', $request->coupon_name)
                    ->where('coupon_validity', '>=', now()->format('Y-m-d'))
                    ->first();
    $carts = Cartbox::with('product')->where('user_id', $id)->latest()->get();

    $totalPrice = 0;
    foreach($carts as $cart){
        $totalPrice += $cart->price * $cart->quantity;
    }

    if ($coupon) {
        $request->session()->put('coupon', [
            'coupon_name' => $coupon->coupon_name, 
            'coupon_discount' => $coupon->coupon_discount, 
            'discount_amount' => round($totalPrice * $coupon->coupon_discount / 100), 
            'total_amount' => round($totalPrice - $totalPrice * $coupon->coupon_discount / 100)
        ]);

        return response()->json([
            'validity' => true,                
            'success' => 'Coupon Applied Successfully'
        ]);
    } else {
        return response()->json(['error' => 'Invalid Coupon']);
    }
}


public function CouponCalculation()
{
    $id = session('user_id');
    $carts = Cartbox::with('product')->where('user_id', $id)->latest()->get();
        $totalPrice = 0;
        
        foreach ($carts as $cart) {
            $totalPrice += $cart->price * $cart->quantity;
        }
    if (session()->has('coupon')) {
        

        return response()->json([
            'subtotal' => $totalPrice,
            'coupon_name' => session('coupon')['coupon_name'],
            'coupon_discount' => session('coupon')['coupon_discount'],
            'discount_amount' => session('coupon')['discount_amount'],
            'total_amount' => session('coupon')['total_amount'], 
        ]);
    } else {
        return response()->json([
            'total' => $totalPrice,
        ]);
    } 
}

public function CouponRemove(){

    Session::forget('coupon');
    return response()->json(['success' => 'Coupon Remove Successfully']);

}// End Method


public function CheckoutCreate(){

    if (session()->has('user_id')) {
        $id = session('user_id');

        $carts = Cartbox::with('product')->where('user_id', $id)->latest()->get();
        $totalPrice = 0;
        
        foreach ($carts as $cart) {
            $totalPrice += $cart->price * $cart->quantity;
        }


        if ($totalPrice > 0) { 
            $id = session('user_id');
        $carts = Cartbox::with('product')->where('user_id',$id)->latest()->get();
        $cartQty = Cartbox::with('product')->where('user_id',$id)->count();

 
    $cartTotal = $totalPrice;

    $divisions = ShipDivision::orderBy('division_name','ASC')->get();

        return view('frontend.checkout.checkout_view',compact('carts','cartQty','cartTotal','divisions'));


        }else{

        $notification = array(
        'message' => 'Shopping At list One Product',
        'alert-type' => 'error'
    );

    return redirect()->to('/')->with($notification); 
        }



    }else{

         $notification = array(
        'message' => 'You Need to Login First',
        'alert-type' => 'error'
    );

    return redirect()->route('login')->with($notification); 
    }




}// End Method




}
