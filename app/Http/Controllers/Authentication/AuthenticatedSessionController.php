<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */


     public function store(LoginRequest $request)
{
    $remember = $request->has('remember');

    // Authenticate the user
    $request->authenticate($remember);
    
    // After authentication, check the user's role
    $userRole = $request->user()->role;

    // If the user role is not 'admin' and you are in the admin panel,
    // you should redirect them with an error message.
    if (request()->is('admin/*')) { // Check if the request is to the admin panel
        if ($userRole !== 'admin') {
            // User is not an admin but trying to access the admin panel
            return redirect()->back()->withErrors(['You do not have access to the admin panel']);
        }
    }

    // If the user role is valid, continue with session regeneration and redirection
    $request->session()->regenerate();

    // Determine the URL for redirection based on the user's role
    $url = '';
    if ($userRole === 'admin') {
        $url = 'admin/dashboard';
    } elseif ($userRole === 'vendor') {
        $url = 'vendor/dashboard';
    } elseif ($userRole === 'user') {
        $url = '/dashboard';
    }

    // Notification message for successful login
    $notification = [
        'message' => 'Login Successfully',
        'alert-type' => 'success',
    ];

    // Redirect to the determined URL with the notification
    return redirect()->intended($url)->with($notification);
}




//      public function store(LoginRequest $request)
// {
//     $remember = $request->has('remember');

//     $request->authenticate($remember);

//     $request->session()->regenerate();

//     $notification = [
//         'message' => 'Login Successfully',
//         'alert-type' => 'success',
//     ];

//     $url = '';
//     if ($request->user()->role === 'admin') {
//         $url = 'admin/dashboard';
//     } elseif ($request->user()->role === 'vendor') {
//         $url = 'vendor/dashboard';
//     } elseif ($request->user()->role === 'user') {
//         $url = '/dashboard';
//     }

//     return redirect()->intended($url)->with($notification);
// }



    // public function store(LoginRequest $request)
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //       $notification = array(
    //         'message' => 'Login Successfully',
    //         'alert-type' => 'success'
    //     );

    //     $url = '';
    //     if ($request->user()->role === 'admin') {
    //         $url = 'admin/dashboard';
    //     } elseif ($request->user()->role === 'vendor') {
    //         $url = 'vendor/dashboard';
    //     } elseif ($request->user()->role === 'user') {
    //         $url = '/dashboard';
    //     }

    //     return redirect()->intended($url)->with($notification);
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
