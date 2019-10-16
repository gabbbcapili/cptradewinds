<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Payment;
use App\Dollar;
use App\Mail\NewPaymentRateMail;
use Illuminate\Mail\Mailer;
use App\Mail\BankDetails;
use Carbon\Carbon;

class ComputePaymentRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:paymentrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re compute payment amounts / reminder mail to buyer.';

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
    public function handle(Mailer $mailer)
    { 
        if (date("Y-m-j H:i:s", strtotime('1 day ago')) < date("Y-m-j H:i:s", strtotime(Dollar::first()->updated_at))) {

            $payments = Payment::whereBetween('status', [4,5])->get();
            
            foreach($payments as $payment){
                 $payment->update(['rate' => Dollar::first()->pluck('rate')->toArray()[0]]);
                 $mailer->to($payment->ordered_by->email)->send(new NewPaymentRateMail($payment));
            }
        }
        $this->info('Successfully sent all emails with the new rate.');
    }
}
