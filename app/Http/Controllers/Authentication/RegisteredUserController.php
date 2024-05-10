<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Notifications\RegisterUserNotification;
use Illuminate\Support\Facades\Notification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

     public function store(Request $request): RedirectResponse
{
    // Validate the request data
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'password_confirmation' => ['required'],
        'checkbox' => ['required', 'accepted'],
    ], [
        'password.min' => 'The password must be at least 8 characters long.',
        'password.confirmed' => 'The password confirmation does not match.',
        'checkbox.accepted' => 'You must accept the terms and conditions.',
    ]);

    // Create a new user and hash the password
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Fire Registered event to notify other parts of the application
    event(new Registered($user));

    // Log the user in
    Auth::login($user);

    // Send a notification to admin users about the new registration
    $adminUsers = User::where('role', 'admin')->get();
    Notification::send($adminUsers, new RegisterUserNotification($user));

    // Redirect the user to the dashboard
    return redirect()->route('dashboard')->with('status', 'Registration successful! Welcome to the dashboard.');
}


//      public function store(Request $request): RedirectResponse
// {
//     // Validate the request data
//     $request->validate([
//         'name' => ['required', 'string', 'max:255'],
//         'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:' . User::class],
//         'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
//         'password_confirmation' => ['required'],
//         'checkbox' => ['required', 'accepted'], // Checkbox for terms and conditions
//     ], [
//         'password' => 'The password must be at least 8 characters long.',
//         'password_confirmation' => 'The password confirmation does not match.',
//         'checkbox' => 'You must accept the terms and conditions.',
//     ]);

//     // Create a new user
//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//     ]);

//     // Fire Registered event
//     event(new Registered($user));

//     // Log the user in
//     Auth::login($user);

//     // Send a notification to admin users about the new registration
//     $adminUsers = User::where('role', 'admin')->get();
//     Notification::send($adminUsers, new RegisterUserNotification($request));

//     // Redirect the user to the dashboard
//     return redirect(route('dashboard'));
// }



    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);
    //     $nuser = User::where('role','admin')->get();
    //      Notification::send($nuser, new RegisterUserNotification($request));
    //     return redirect(route('dashboard', absolute: false));
    // }
}
