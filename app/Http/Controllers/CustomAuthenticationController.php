<?php

namespace App\Http\Controllers;

use App\Mail\SubscribeMail;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Notifications\VendorRegNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;


class CustomAuthenticationController extends Controller
{
    public function userlogin()
    {
        return view('auth.login');
    }

    public function userregistration()
    {
        return view('auth.register');
    }

    public function userloginpost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', '=', $request->email)->first();

        if(!$user)
        {
            return redirect()->back()->with('error', 'Invalid Email.');
        }

       else if(!($user->role == 'user'))
        {
            return redirect()->back()->with('error', 'Not a user.');
        }


        else if($user)
        {
            if(Hash::check($request->password, $user->password) && $user->role == 'user')
            {
                Session::put('user_id', $user->id);
               
                return redirect()->route('dashboard');
            }
            else
            {
                return redirect()->back()->with('error', 'Invalid password.');
            }

        }
        else 
        {
            return redirect()->back()->with('error', 'Invalid email');
        }
    }

    public function userregistrationpost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation'=> 'required|same:password',
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();
        
        if(!$user){
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        else
        {
            return redirect()->route('userlogin')->with('success', 'You are registered successfully.');
        }
    }

    public function userlogout()
    {
        if(Session::has('user_id'))
        {
            Session::pull('user_id');
        }
        return redirect()->route('userlogin');
    }


    public function adminlogin()
    {
        return view('admin.admin_login');
    }// end method  

    public function adminloginpost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', '=', $request->email)->first();

        if(!$user)
        {
            return redirect()->back()->with('error', 'Invalid Email.');
        }

       else if(!($user->role == 'admin'))
        {
            return redirect()->back()->with('error', 'Not a admin.');
        }


        else if($user)
        {
            if(Hash::check($request->password, $user->password) && $user->role == 'admin')
            {
                Session::put('admin_id', $user->id);
               
                return view('admin.index');
            }
            else
            {
                return redirect()->back()->with('error', 'Invalid password.');
            }

        }
        else 
        {
            return redirect()->back()->with('error', 'Invalid email');
        }
    }

    public function adminlogout()
    {
        if(Session::has('admin_id'))
        {
            Session::pull('admin_id');
        }
        return view('admin.admin_login');
    }

    public function vendorlogin()
    {
        return view('vendor.vendor_login');
    }

    public function vendorregistration()
    {
        return view('auth.become_vendor');
    }

    public function vendorregistrationpost(Request $request) {

        $vuser = User::where('role','admin')->get();

        $isemail = User::where('email', $request->email)->first();
        if($isemail)
        {
            return redirect()->back()->with('error', 'Email already exists.');
        }
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

    public function vendorloginpost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', '=', $request->email)->first();

        if(!$user)
        {
            return redirect()->back()->with('error', 'Invalid Email.');
        }

       else if(!($user->role == 'vendor'))
        {
            return redirect()->back()->with('error', 'Not a vendor.');
        }


        else if($user)
        {
            if(Hash::check($request->password, $user->password) && $user->role == 'vendor')
            {
                Session::put('vendor_id', $user->id);
               
                return view('vendor.index');
            }
            else
            {
                return redirect()->back()->with('error', 'Invalid password.');
            }

        }
        else 
        {
            return redirect()->back()->with('error', 'Invalid email');
        }
    } // end methods

    public function vendorlogout()
    {
        if(Session::has('vendor_id'))
        {
            Session::pull('vendor_id');
        }
        return view('vendor.vendor_login');
    }


    public function UserSubscribe(Request $request)
    {
        $email = $request->email;
    
        // Check if the email already exists
        $existingSubscription = Subscribe::where('email', $email)->first();
    
        if ($existingSubscription) {
            return redirect()->back()->with('info', 'You are already subscribed.');
        }
    
        // Insert the email if it doesn't exist
        Subscribe::create([
            'email' => $email,
        ]);
    
        return redirect()->back()->with('success', 'Subscribed Successfully.');
    }

    public function MsgSent (Request $request)
    {
        
        
        $data = [

            'mess' => $request->email
        ];

        $recipients = Subscribe::all();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(new SubscribeMail($data));
        }

        return redirect()->back()->with('success', 'Message sent successfully.');


    }


}
