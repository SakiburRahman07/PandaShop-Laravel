<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetVendor extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Constructor to initialize the mailable with the user object.
     *
     * @param object $user The user who requested a password reset.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the email message.
     *
     * @return $this The current instance of the mailable.
     */
    public function build()
    {
        // Use markdown template for the email and set the subject
        return $this->subject(config('app.name') . ', Forgot Password')
            ->markdown('emails.vendor_forgot_password');
    }
}
