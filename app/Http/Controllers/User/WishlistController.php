<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product; // Ensure to import the Product model
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WishlistController extends Controller
{
    public function AddToWishList(Request $request, $product_id)
    {
        if (session()->has('user_id')) {
            $exists = Wishlist::where('user_id', session('user_id'))->where('product_id', $product_id)->first();

            if (!$exists) {
                Wishlist::create([
                    'user_id' => session('user_id'),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Successfully Added to Your Wishlist']);
            } else {
                return response()->json(['error' => 'This Product is Already in Your Wishlist']);
            }
        } else {
            return response()->json(['error' => 'Please log in to your account first']);
        }
    }

    public function AllWishlist()
    {
        // Assuming the user is authenticated and you have a session user_id
        $userId = session('user_id');

        // Fetch the wishlist items with related products
        $wishlist = Wishlist::with('product')->where('user_id', $userId)->get();

        // Pass the wishlist data to the view
        return view('frontend.wishlist.view_wishlist', compact('wishlist'));
    }

    public function GetWishlistProduct()
    {
        $wishlist = Wishlist::with('product')->where('user_id', session('user_id'))->latest()->get();
        $wishQty = Wishlist::where('user_id', session('user_id'))->count();

        return response()->json(['wishlist' => $wishlist, 'wishQty' => $wishQty]);
    }

    // public function WishlistRemove($id)
    // {
    //     Wishlist::where('user_id', session('user_id'))->where('id', $id)->delete();
    //     return response()->json(['success' => 'Successfully Removed Product']);
    // }

    public function wishlistRemove($id)
    {
        // Ensure the user is authenticated and owns the wishlist item
        $deleted = Wishlist::where('user_id', session('user_id'))->where('id', $id)->delete();
        
        if ($deleted) {
            return redirect()->back()->with('success', 'Successfully Removed Product');
        } else {
            return redirect()->back()->with('error', 'Error removing product');
        }
    }
}
