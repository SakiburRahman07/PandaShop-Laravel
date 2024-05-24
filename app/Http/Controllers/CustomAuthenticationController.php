<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

    public function registrationpost(Request $request)
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
    }

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

}
