<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    //
    public function UserDashboard(){

        //$id = Auth::user()->id;
       // $userData = User::find($id);
       $userData = array();
       if(Session::has('user_id')){
           $userData = User::find(Session::get('user_id'));
         }
        return view('index',compact('userData'));

    } // End Method 

    public function UserProfileStore(Request $request){
        
        $id = session('user_id');
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->phone = $request->phone;
        $data->address = $request->address; 


        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/'.$data->photo));
          //  $filename = date('YmdHi').$file->getClientOriginalName();
            $filename = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            $file->move(public_path('upload/user_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Mehtod 

    public function UserLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

         $notification = array(
            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    } // End Mehtod  

    public function UserUpdatePassword(Request $request){
        // Validation 
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        $id = session('user_id');
        $user = \App\Models\User::find($id);

        // Match The Old Password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        // Update The new password 
        User::whereId($id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");

    } // End Mehtod 







}
