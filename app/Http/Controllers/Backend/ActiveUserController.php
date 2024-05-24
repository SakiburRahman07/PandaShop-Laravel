<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ActiveUserController extends Controller
{
    public function AllUser(){
        
        $users = User::where('role','user')->latest()->get();
        return view('backend.user.user_all_data',compact('users'));

    } // End Mehtod 

    public function AllVendor(){
        $vendors = User::where('role','vendor')->latest()->get();
        return view('backend.user.vendor_all_data',compact('vendors'));

    } // End Mehtod 

    public function DeleteUser($id)
    {
        $user = User::findOrFail($id);
        $img = public_path('upload/user_images/') . $user->photo;
        
        if (file_exists($img)) {
            unlink($img); 
        }
        
        $user->delete();
        
        $notification = [
            'message' => 'User Deleted Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect()->back()->with($notification); 
    }

    public function DeleteVendor($id)
    {
        $user = User::findOrFail($id);
        $img = public_path('upload/vendor_images/') . $user->photo;
        
        if (file_exists($img)) {
            unlink($img); 
        }
        
        $user->delete();
        
        $notification = [
            'message' => 'Vendor Deleted Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect()->back()->with($notification); 
    }
    
}
