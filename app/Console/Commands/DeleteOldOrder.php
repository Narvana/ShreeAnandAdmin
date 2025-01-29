<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\HttpKernel\Log\Logger;

class DeleteOldOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check of 15 days old order and delete them';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $Orders = Order::get();

        $removeOrderIds = [];

        foreach ($Orders as $Order) {
            // Fetch the user associated with the subscription
        
            // $now = Carbon::now();

            $firstDate = Carbon::parse($Order->OrderDate);

            // $checkDate = $firstDate->addDate(15);

            if ($firstDate->addDays(15)->isPast()) {
                array_push($removeOrderIds,$Order->id);
            }
        }
        // Delete the orders in one go for better performance
        if (!empty($removeOrderIds)) {
            // $this->info(json_encode($removeOrderIds,true));
            Order::destroy($removeOrderIds); // Delete orders by their IDs
            $this->info('Deleted orders: ' . implode(', ', $removeOrderIds));
        } else {
            $this->info('No orders to delete.');
        }

        $this->info('Order Check Complete');

    }
}
