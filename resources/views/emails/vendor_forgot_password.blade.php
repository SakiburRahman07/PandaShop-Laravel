@component('mail::message')

# Password Reset Request

Hi {{ $user->name }},

We received a request to reset your password. No need to worry—these things happen! Just click the button below to reset your password and get back to shopping.

@component('mail::button', ['url' => url('reset/vendor/'.$user->remember_token), 'color' => 'primary'])
Reset Password
@endcomponent

If you did not request a password reset, please ignore this email. Your account is safe.

Thank you,

{{ config('app.name') }} Team

@endcomponent
