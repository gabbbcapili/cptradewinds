<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsuranceDepositMail extends Mailable
{
    use Queueable, SerializesModels;

    public $insurance;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))->view('mails.InsuranceDeposit')->subject(env('PROJECT_NAME') . ' - Insurance Services');
    }
}
