<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; 
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    public function VendorOrder(){

        $id = session('vendor_id');
        $orderitem = OrderItem::with('order')->where('vendor_id',$id)->orderBy('id','DESC')->get();
        return view('vendor.backend.orders.pending_orders',compact('orderitem'));
    } // End Method 

    public function VendorReturnOrder(){

        $id = session('vendor_id');
       $orderitem = OrderItem::with('order')->where('vendor_id',$id)->orderBy('id','DESC')->get();
       return view('vendor.backend.orders.return_orders',compact('orderitem'));

   } // End Method 

   public function VendorCompleteReturnOrder(){

    $id = session('vendor_id');
   $orderitem = OrderItem::with('order')->where('vendor_id',$id)->orderBy('id','DESC')->get();
   return view('vendor.backend.orders.complete_return_orders',compact('orderitem'));

} // End Method 

public function VendorOrderDetails($order_id){

    $order = Order::with('division','district','state','user')->where('id',$order_id)->first();
    $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

    return view('vendor.backend.orders.vendor_order_details',compact('order','orderItem'));

}// End Method 

}
