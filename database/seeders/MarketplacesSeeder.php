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
        $marketplace_Falabella = new Marketplace();
        $marketplace_Falabella->connector_class_name = 'Falabella';
        $marketplace_Falabella->name = 'Falabella';
        $marketplace_Falabella->save();

        $falabella_name_configs_array = [
            'UserID',
            'ApiKey',
            'SellerID',
            //'callbackUrl'
        ];
        foreach( $falabella_name_configs_array as $name_config ){
            MarketplaceConfig::create(['name' => $name_config, 'marketplace_id' => $marketplace_Falabella->marketplaces_id]);
        }


    }
}
