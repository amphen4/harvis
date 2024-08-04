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
            $className = 'App\\Http\Connectors\\'.$marketplace->connector_class_name;
            if( class_exists($className) ){
                $marketplaceShops = $marketplace->shops()->get();
                foreach( $marketplaceShops as $shop ){
                    $connector = new $className($marketplace, $shop);
                    if( method_exists($connector, 'isTokenExpired') && $connector->isTokenExpired() ){
                        if( method_exists($connector, 'refreshToken') ){
                            $connector->refreshToken();
                        }
                    }
                } 
            }
        }
    }
}
