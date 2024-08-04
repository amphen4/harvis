<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Marketplace;

class RefreshApiTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-api-tokens-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresca tokens de APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $marketplaces = Marketplace::all();
        foreach( $marketplaces as $marketplace ){
            $marketplaceShops = $marketplace->shops()->get();
            \Log::info("Prueba comandos programados");
            \Log::info(json_encode($marketplaceShops));
        }
    }
}
