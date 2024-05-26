<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Carbon;

class ReviewController extends Controller
{
    public function StoreReview(Request $request)
    {
        $product = $request->product_id;
        $vendor = $request->hvendor_id;
        $userId = session('user_id');
    
        $request->validate([
            'comment' => 'required',
        ]);
    
        // Check if the user has ordered the product
        $hasOrdered = Order::
            join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $userId)
            ->where('order_items.product_id', $product)
            ->exists();
    
        if (!$hasOrdered) {
            $notification = [
                'message' => 'You can only review products you have ordered.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with(  $notification);
        }
    
        // Check if the user has already reviewed the product
        $hasReviewed = Review::
            where('user_id', $userId)
            ->where('product_id', $product)
            ->exists();
    
        if ($hasReviewed) {
            $notification = [
                'message' => 'You have already reviewed this product.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with(  $notification);
        }
    
        // Insert the review
        Review::insert([
            'product_id' => $product,
            'user_id' => $userId,
            'comment' => $request->comment,
            'rating' => $request->quality,
            'vendor_id' => $vendor,
            'created_at' => Carbon::now(),
        ]);
    
        $notification = [
            'message' => 'Review Will Approve By Admin',
            'alert-type' => 'success'
        ];
    
        return redirect()->back()->with($notification);
    } // End Method
    

    public function PendingReview(){

        $review = Review::where('status',0)->orderBy('id','DESC')->get();
        return view('backend.review.pending_review',compact('review'));

    }// End Method

    public function ReviewApprove($id){

        Review::where('id',$id)->update(['status' => 1]);

        $notification = array(
            'message' => 'Review Approved Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method 

    public function PublishReview(){

        $review = Review::where('status',1)->orderBy('id','DESC')->get();
        return view('backend.review.publish_review',compact('review'));

    }// End Method 


    public function ReviewDelete($id){

        Review::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Review Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }// End Method 

    public function VendorAllReview(){

        $id = session('user_id');

        $review = Review::where('vendor_id',$id)->where('status',1)->orderBy('id','DESC')->get();
        return view('vendor.backend.review.approve_review',compact('review'));

    }// End Method 
}
