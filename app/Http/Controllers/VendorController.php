<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VendorRegNotification;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetVendor;
//use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Requests\ResetPassword;

class VendorController extends Controller
{
    public function VendorDashboard()
    {
        return view('vendor.index');
    }// end method  
    
    public function VendorLogin(){
        return view('vendor.vendor_login');
    } // End Mehtod 

    public function VendorDestroy(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    } // End Mehtod 

    public function VendorProfile(){

        $id = session('vendor_id');
        $vendorData = User::find($id);
        return view('vendor.vendor_profile_view',compact('vendorData'));

    } // End Mehtod 

    public function VendorProfileStore(Request $request){

        $id = session('vendor_id');
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 
        $data->vendor_join = $request->vendor_join; 
        $data->vendor_short_info = $request->vendor_short_info; 


        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/vendor_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Vendor Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Mehtod 

    public function VendorChangePassword(){
        return view('vendor.vendor_change_password');
    } // End Mehtod 



public function VendorUpdatePassword(Request $request){
        // Validation 
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        $id = session('vendor_id');
        $vendor = \App\Models\User::find($id);

        // Match The Old Password
        if (!Hash::check($request->old_password, $vendor->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        // Update The new password 
        User::whereId($id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");

    } // End Mehtod 

    public function BecomeVendor(){
        return view('auth.become_vendor');
    } // End Mehtod 

    public function VendorRegister(Request $request) {

        $vuser = User::where('role','admin')->get();


        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::insert([ 
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'vendor_join' => $request->vendor_join,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => 'inactive',
        ]);

          $notification = array(
            'message' => 'Vendor Registered Successfully',
            'alert-type' => 'success'
        );

        Notification::send($vuser, new VendorRegNotification($request));

        return redirect()->route('vendor.login')->with($notification);

    }// End Mehtod 

    public function VendorForgetPassword()
    {
        // Return the password reset form view
        return view('vendor.vendor_forget_password');
    }

    public function VendorForgot(Request $request)
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
            Mail::to($user->email)->send(new PasswordResetVendor($user));
    
            // Redirect back with a success message
            return redirect()->back()->with('status', 'Password reset link sent to your email.');
        } else {
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Email not found.');
        }
    }

    public function VendorReset(Request $request, $token)
    {
        $user=User::where('remember_token', '=', $token);
        if($user->count()==0)
        {
            abort(403);
        }
        $user = $user->first();
        $data['token'] = $token;
        
        return view('vendor.vendor_reset_password', $data);
    }

    public function postVendorReset($token, ResetPassword $request)
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

        return redirect('/vendor/login')->with('status', 'Password reset successfully.');
    }





}
