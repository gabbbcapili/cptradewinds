<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SupplierMail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class SendEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminder to customer.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        
       // Mail::to('hey@gmail.com')->send(new SupplierMail('sASsA'));
        $this->info('adsds');
    }
}
