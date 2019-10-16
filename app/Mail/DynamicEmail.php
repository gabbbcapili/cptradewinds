<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;
    public $view;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject, $view)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))->view($this->view)->subject($this->subject);
    }
}
