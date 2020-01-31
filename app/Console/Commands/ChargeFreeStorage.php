<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;

class ChargeFreeStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:chargeFreeStorage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge all orders that has importation cost that has exceeded 3 days by 400php per cbm per day';

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
        $orders = Order::whereIn ('status', ['11', '12', '13'])->whereNotNull('price_date')->get();
        foreach($orders as $order){
            $order_date = \Carbon::parse($order->price_date);
            $now = \Carbon::now();
            $diff = $order_date->diffInDays($now);
            if($diff > 3){
                $cbm_extra_charges = $order->boxes_received * 400;
                $cbm_extra_charges = $order->extra_charges + $cbm_extra_charges;
                $order->update(['extra_charges' => $cbm_extra_charges]);
            }
        }
    }
}
