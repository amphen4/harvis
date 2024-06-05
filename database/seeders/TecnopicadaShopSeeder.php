<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use App\Models\MarketplaceConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class TecnopicadaShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if( !Shop::where('name', 'Tecnopicada')->count() ){
            $shop = new Shop;
            $shop->name = 'Tecnopicada';
            $shop->save();
        }
        

        

        /** Configuraciones Falabella */
        $falabellaMarketplace = Marketplace::where('name','Falabella')->first();
        $shop->marketplaces()->sync( [ $falabellaMarketplace->marketplaces_id => ['shop_alias' => 'TECNOPICADA GADGETSHOP'] ] );
        $shop->save();

        $falabellaMarketplaceConfigs = MarketplaceConfig::where('marketplace_id',$falabellaMarketplace->marketplaces_id)->get();
        foreach( $falabellaMarketplaceConfigs as $falabellaMarketplaceConfig ){
            switch( $falabellaMarketplaceConfig->name ){
                case 'UserID':
                    DB::table('shop_marketplace_configs')->insert([
                        'marketplace_config_id' => $falabellaMarketplaceConfig->marketplace_configs_id,
                        'shop_id' => $shop->shops_id,
                        'value' => json_encode("ventas@tecnopicada.cl"),
                    ]);
                break;
                case 'ApiKey':
                    DB::table('shop_marketplace_configs')->insert([
                        'marketplace_config_id' => $falabellaMarketplaceConfig->marketplace_configs_id,
                        'shop_id' => $shop->shops_id,
                        'value' => json_encode("f23a6ba21365228739f585ca10eb90a525519dee"),
                    ]);
                break;
                case 'SellerID':
                    DB::table('shop_marketplace_configs')->insert([
                        'marketplace_config_id' => $falabellaMarketplaceConfig->marketplace_configs_id,
                        'shop_id' => $shop->shops_id,
                        'value' => json_encode("SC9FECB"),
                    ]);
                break;
                default:
                break;
            }
        }
    }
}
