<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\Marketplace;
use App\Models\MarketplaceApiCronJob;

class ExecMarketplaceApiCronCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:exec-marketplace-api-cron-command {shop_id} {marketplace_id} {cron_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shop = Shop::findOrFail($this->argument('shop_id'));
        $marketplace = Marketplace::findOrFail($this->argument('marketplace_id'));
        $cron = MarketplaceApiCronJob::findOrFail($this->argument('cron_id'));
        
    }
}
