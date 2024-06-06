<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marketplace;
use App\Models\MarketplaceConfig;
use Illuminate\Support\Facades\DB;

class MarketplacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if( !Marketplace::where('name','Falabella')->count() ){
            $marketplace_Falabella = new Marketplace();
            $marketplace_Falabella->connector_class_name = 'Falabella';
            $marketplace_Falabella->name = 'Falabella';
            $marketplace_Falabella->save();
        }else{
            $marketplace_Falabella = Marketplace::where('name','Falabella')->first();
        }
        $falabella_name_configs_array = [
            'UserID',
            'ApiKey',
            'SellerID',
            //'callbackUrl'
        ];
        foreach( $falabella_name_configs_array as $name_config ){
            if( !MarketplaceConfig::where('name', $name_config)->count() ){
                MarketplaceConfig::create(['name' => $name_config, 'marketplace_id' => $marketplace_Falabella->marketplaces_id]);
            }
            
        }

        if( !Marketplace::where('name','Paris')->count() ){
            $marketplace_Paris = new Marketplace();
            $marketplace_Paris->connector_class_name = 'Paris';
            $marketplace_Paris->name = 'Paris';
            $marketplace_Paris->save();
        }else{
            $marketplace_Paris = Marketplace::where('name','Paris')->first();
        }
        $paris_name_configs_array = [
            'ApiKey',
        ];
        foreach( $paris_name_configs_array as $name_config ){
            if( !MarketplaceConfig::where('name', $name_config)->where('marketplace_id',$marketplace_Paris->marketplaces_id)->count() ){
                MarketplaceConfig::create(['name' => $name_config, 'marketplace_id' => $marketplace_Paris->marketplaces_id]);
            }
        }

    }
}
