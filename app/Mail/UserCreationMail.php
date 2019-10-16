<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->user->role == 'customer'){
            return $this->from(env('MAIL_FROM_ADDRESS'))->view('mails.CustomerCreationMail')->subject(env('PROJECT_NAME') . ' - Welcome!');
        }
        if($this->user->role == 'supplier'){
            return $this->from(env('MAIL_FROM_ADDRESS'))->view('mails.SupplierCreationMail')->subject(env('PROJECT_NAME') . ' - Welcome!');
        }

        
        
    }
}
