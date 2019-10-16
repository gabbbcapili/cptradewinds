<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        //
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))->view('mails.LoginCredentials')->subject('Login Credentials');
    }
}
