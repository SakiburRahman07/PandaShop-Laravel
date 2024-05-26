<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VendorApproveNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetAdmin;
//use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Requests\ResetPassword;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    }// end method  

    public function AdminLogin()
    {
        return view('admin.admin_login');
    }// end method  

    public function AdminDestroy(Request $request): RedirectResponse 
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
    //end method 

    public function AdminProfile(){
        $id = session('admin_id');
       
        $adminData = User::find($id);
        return view('admin.admin_profile_view',compact('adminData'));

    } // End Mehtod 

    public function AdminProfileStore(Request $request){
        $id = session('admin_id');
        
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Mehtod 

    public function AdminChangePassword(){
        return view('admin.admin_change_password');
    } // End Mehtod 

    public function AdminUpdatePassword(Request $request){
        // Validation 
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        $id = session('admin_id');
        $admin = \App\Models\User::find($id);

        // Match The Old Password
        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }
        $id = session('admin_id');

        // Update The new password 
        User::whereId($id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");

    } // End Mehtod 

    public function InactiveVendor(){
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return view('backend.vendor.inactive_vendor',compact('inActiveVendor'));

    }// End Mehtod 

    public function ActiveVendor(){
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('backend.vendor.active_vendor',compact('ActiveVendor'));

    }// End Mehtod 

    public function InactiveVendorDetails($id){

        $inactiveVendorDetails = User::findOrFail($id);
        return view('backend.vendor.inactive_vendor_details',compact('inactiveVendorDetails'));

    }// End Mehtod 

    public function ActiveVendorApprove(Request $request){

        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor Active Successfully',
            'alert-type' => 'success'
        );

        $vuser = User::where('role','vendor')->get();
        Notification::send($vuser, new VendorApproveNotification($request));

        return redirect()->route('active.vendor')->with($notification);

    }// End Mehtod 

    public function ActiveVendorDetails($id){

        $activeVendorDetails = User::findOrFail($id);
        return view('backend.vendor.active_vendor_details',compact('activeVendorDetails'));

    }// End Mehtod 


     public function InActiveVendorApprove(Request $request){

        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor InActive Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('inactive.vendor')->with($notification);

    }// End Mehtod 


    //---------------------------------------------------admin panel er password neye kaj-----------------------------

    // In AdminController.php
    public function AdminForgetPassword()
    {
        // Return the password reset form view
        return view('admin.admin_forget_password');
    }

    public function AdminForgot(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email'
        ]);
    
        // Retrieve user by email
        $user = User::where('email', '=', $request->email)->first();
    
        if ($user) {
            // Generate a new token and save it
            $user->remember_token = Str::random(50);
            $user->save();
    
            // Send password reset email
            Mail::to($user->email)->send(new PasswordResetAdmin($user));
    
            // Redirect back with a success message
            return redirect()->back()->with('status', 'Password reset link sent to your email.');
        } else {
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Email not found.');
        }
    }

    public function AdminReset(Request $request, $token)
    {
        $user=User::where('remember_token', '=', $token);
        if($user->count()==0)
        {
            abort(403);
        }
        $user = $user->first();
        $data['token'] = $token;
        
        return view('admin.admin_reset_password', $data);
    }
    

    public function postAdminReset($token, ResetPassword $request)
    {
        $user = User::where('remember_token', '=', $token);
        if($user->count()==0)
        {
            abort(403);
        }
        $user = $user->first();
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(50);
        $user->save();

        return redirect('/admin/login')->with('status', 'Password reset successfully.');
    }

    



}
