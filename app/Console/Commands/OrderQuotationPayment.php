<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Mail\DynamicEmail;
use Illuminate\Mail\Mailer;

class OrderQuotationPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Continue Orders Quotation that has Payment';

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
        $orders = Order::where('status', '15')->where('withQuote', true)->get();
        foreach($orders as $order){
            $payments = $order->payments->where('status', 7)->first();
            if($payments){
                $order->update(['status' => 3, 'withQuote' => false]);
                $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, 'Customer payment details', 'mails.order.CustomerPayment'));
            }
        }
    }
}
