<?php

namespace App\Mail;

use App\Models\User;
use Config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoogleAuthenticatorOtpEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this
            ->view('emails.users.verify_google_authenticator')
            ->from(config('variable.ADMIN_EMAIL'), config('variable.SITE_NAME'))
            ->subject(config('app.name') . ': ' . 'Google Authenticator Verification');
    }
}
