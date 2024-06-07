<?php

namespace App\Console\Commands;

use App\DataTranslators\Falabella as DataTranslatorsFalabella;
use App\DataTranslators\Paris as DataTranslatorsParis;
use App\Http\Connectors\Falabella;
use App\Http\Connectors\Paris;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\ProductVariant;
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
        $contadorProductosCreados = 0;
   
        if( $marketplace->name == 'Falabella' ){
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
                    if( !Product::where('shop_id', $shop->shops_id)
                        ->where('marketplace_id', $marketplace->marketplaces_id)
                        ->where('sku', $dataTranslated['sku'])
                        ->count() 
                    ){
                        Product::create( $dataProduct );
                        $contadorProductosCreados++;
                    }
                    
                }
            }
        }
        if( $marketplace->name == 'Paris' ){
            
            $serviceConnector = new Paris($marketplace, $shop);
            $productosMarketplace = [];
            // Paginación
            $prices = 'true';
            $page = 0;
            $limit = 25;
            $json_response = $serviceConnector->getProducts([
                'prices' => $prices,
                'offset' => $page,
                'limit' => $limit
            ]);
            if(
                $json_response &&
                intval($json_response->total)
            ){
                while( true ){
                    $productosMarketplace = array_merge($productosMarketplace, $json_response->results);
                    $page++;
                    if( (($page+1)*$limit) >= intval($json_response->total) ){
                        break;
                    }
                    $json_response = $serviceConnector->getProducts([
                        'prices' => $prices,
                        'offset' => $page,
                        'limit' => $limit
                    ]);
                }
                foreach( $productosMarketplace as $productoMarketplace ){
                    $dataTranslated = DataTranslatorsParis::translateProduct( $productoMarketplace );
                    $dataProduct = array_merge($dataTranslated, [
                        'shop_id' => $shop->shops_id, 
                        'marketplace_id' => $marketplace->marketplaces_id,
                        'created_at' => (new Carbon)->toDateTimeString(),
                        'updated_at' => (new Carbon)->toDateTimeString()
                    ]);
                    if( !Product::where('shop_id', $shop->shops_id)
                        ->where('marketplace_id', $marketplace->marketplaces_id)
                        ->where('sku', $dataTranslated['sku'])
                        ->count() 
                    ){
                        $product = Product::create( $dataProduct );
                        $contadorProductosCreados++;
                        if( $productoMarketplace->variants && count($productoMarketplace->variants) ){
                            foreach( $productoMarketplace->variants as $variant ){
                                $dataTranslated = DataTranslatorsParis::translateProductVariant($variant);
                                $dataProductVariant = array_merge($dataTranslated, [
                                    'name' => $product->name,
                                    'product_id' => $product->products_id,
                                    'created_at' => (new Carbon)->toDateTimeString(),
                                    'updated_at' => (new Carbon)->toDateTimeString()
                                ]);
                                if( !ProductVariant::where('product_id', $product->products_id)
                                    ->where('sku', $dataTranslated['sku'])
                                    ->where('price', $dataTranslated['price'])
                                    ->count() 
                                ){
                                    ProductVariant::create( $dataProductVariant );
                                }
                            }
                        }
                    }
                    
                }
                
            }
        }
        $this->line('Productos creados en BD: '.$contadorProductosCreados);
    }
}
