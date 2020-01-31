<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;

class OrderAutomatedSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:automatedsms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automated SMS for Order Processing';

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
        $orders = Order::where('status', '>=', '8')->whereNotNull('shipped_at')->get();
        foreach($orders as $order){
            if($order->ordered_by->phone_no == null){
                return;
            }
            $order_date = \Carbon::parse($order->shipped_at);
            $now = \Carbon::now();
            $diff = $order_date->diffInDays($now);
            if($diff == 3){
                $order->automatedDay3SMS();
            }
            if($diff == 4){
                $order->automatedDay4SMS();
            }
            if($diff == 6){
                $order->automatedDay6SMS();
            $order->update(['status' => 9]);
            }

            
        }
    }
}
