<?php

namespace App\Http\Connectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Marketplace;
use App\Models\Shop;
use Error;

class Falabella {

    const API_BASE_URL = "https://sellercenter-api.falabella.com";

    protected $marketplace;
    protected $shop;
    protected $shopConfigsData;

    function __construct(Marketplace $falabella, Shop $shop){
        $this->marketplace = $falabella;
        $this->shop = $shop;
        /** Load Configs */
        $this->shopConfigsData = array();
        foreach( $falabella->marketplaceConfigs()->get() as $marketplaceConfig ){
            $shopMarketplaceConfig =  $marketplaceConfig->shops()->where('shops_id', $this->shop->shops_id)->first();
            if( $shopMarketplaceConfig ){
                $this->shopConfigsData[$marketplaceConfig->name] = json_decode($shopMarketplaceConfig->pivot->value);
            }else{
                throw new Error("El siguiente shop_marketplace_config está perdido: $marketplaceConfig->name");
            }
        }
    }

    public function getProducts()
    {
        // The current time. Needed to create the Timestamp parameter below.
        $now = new \DateTime();
        $timestamp = $now->format(\DateTime::ISO8601);
        $format = 'JSON';
        $userID = $this->shopConfigsData['UserID'];
        $version = '1.0';
        $action = 'GetProducts';
        $filter = 'all';
        // The parameters for our GET request. These will get signed.
        $parameters = array(
            'Action' => $action,
            'Filter' => $filter,
            'Format' => $format,
            'Timestamp' => $timestamp, // The current time formatted as ISO8601
            'UserID' => $userID,
            'Version' => $version // The API version. Currently must be 1.0
        );

        // Sort parameters by name.
        ksort($parameters);

        // URL encode the parameters.
        $encoded = array();
        foreach ($parameters as $name => $value) {
            $encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
        }

        // Concatenate the sorted and URL encoded parameters into a string.
        $concatenated = implode('&', $encoded);

        // The API key for the user as generated in the Seller Center GUI.
        // Must be an API key associated with the UserID parameter.
        $api_key = $this->shopConfigsData['ApiKey'];

        // Compute signature and add it to the parameters.
        $signature = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));

        $uri = $this::API_BASE_URL;
        $uri .= '?Action='.$action.'&Filter='.$filter.'&Format='.$format.'&Timestamp='.$timestamp.'&UserID='.$userID.'&Version='.$version.'&Signature='.$signature;
        
        $response = Http::withHeaders([
            'User-Agent' => $this->shopConfigsData['SellerID']."/PHP/8.1/PROPIA/FACL",
        ])->withoutVerifying()->get($uri);

        return json_decode( $response );
    }

}



