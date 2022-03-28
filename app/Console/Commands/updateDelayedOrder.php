<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateDelayedOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delayed:order:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the order status as delay if estimate delivery time exceed current time';

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
        DB::table('orders')->where('delivery_date', '<=', date('Y-m-d H:i:s'))->update(['status' => 3,'updated_at' => date('Y-m-d H:i:s')]);

        $this->info('Order update has been send successfully');
    }
}
