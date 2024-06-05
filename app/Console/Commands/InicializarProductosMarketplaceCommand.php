<?php

namespace App\Console\Commands;

use App\DataTranslators\Falabella as DataTranslatorsFalabella;
use App\Http\Connectors\Falabella;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Console\Command;
use Carbon\Carbon;

class InicializarProductosMarketplaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketplace:inicializar-productos {marketplaceId} {shopId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializa todos los productos de una tienda de un determinado marketplace';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $marketplace = Marketplace::findOrFail($this->argument('marketplaceId'));
        $shop = Shop::findOrFail($this->argument('shopId'));

        $serviceConnector = new Falabella($marketplace, $shop);
        $json_response = $serviceConnector->getProducts();
        if( 
            $json_response && 
            property_exists($json_response, 'SuccessResponse') && 
            property_exists($json_response->SuccessResponse, 'Body') &&
            property_exists($json_response->SuccessResponse->Body, 'Products') &&
            property_exists($json_response->SuccessResponse->Body->Products, 'Product') 
        ){
            $productosMarketplace = $json_response->SuccessResponse->Body->Products->Product;
            foreach( $productosMarketplace as $productoMarketplace ){
                $dataTranslated = DataTranslatorsFalabella::translateProduct( $productoMarketplace );
                $dataProduct = array_merge($dataTranslated, [
                    'shop_id' => $shop->shops_id, 
                    'marketplace_id' => $marketplace->marketplaces_id,
                    'created_at' => (new Carbon)->toDateTimeString(),
                    'updated_at' => (new Carbon)->toDateTimeString()
                ]);
                Product::create( $dataProduct );
            }
        }
        
    }
}
