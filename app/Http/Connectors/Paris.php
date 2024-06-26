<?php

namespace App\Http\Connectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Marketplace;
use App\Models\Shop;
use Carbon\Carbon;
use Error;

class Paris {

    const API_BASE_URL = "https://api-developers.ecomm-stg.cencosud.com";

    protected $marketplace;
    protected $shop;
    protected $shopConfigsData;

    function __construct(Marketplace $paris, Shop $shop){
        $this->marketplace = $paris;
        $this->shop = $shop;
        /** Load Configs */
        $this->shopConfigsData = array();
        foreach( $paris->marketplaceConfigs()->get() as $marketplaceConfig ){
            $shopMarketplaceConfig =  $marketplaceConfig->shops()->where('shops_id', $this->shop->shops_id)->first();
            if( $shopMarketplaceConfig ){
                $this->shopConfigsData[$marketplaceConfig->name] = json_decode($shopMarketplaceConfig->pivot->value);
            }else{
                throw new Error("El siguiente shop_marketplace_config está perdido: $marketplaceConfig->name");
            }
        }
        /*
        if( !$this->auth() ){
            throw new Error("No se pudo loguear en API Paris Marketplace");
        }
        */
    }
    public function makeRequest($method, $uri, $body = null, $headers = []){
        
        if( !Storage::exists('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json') ){
            $this->auth();
        }
        $file_contents = Storage::get('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json');
        $json = json_decode($file_contents);
        if( $json ){
            $now = new Carbon();
            $fechaExpiracionToken = new Carbon($json->expirationDate);
            if( $now->greaterThan( $fechaExpiracionToken ) ){
                $this->auth();
            }else{
                $this->shopConfigsData['AccessToken'] = $json->accessToken;
            }
            $headers = array_merge($headers, [ 'Authorization' => 'Bearer '.$this->shopConfigsData['AccessToken'] ]);
            \Log::info( json_encode([
                'method' => $method,
                'uri' => $uri,
                'body' => $body,
                'headers' => $headers
            ]) );
            if( strtoupper($method) == 'GET' ){
                return Http::withHeaders($headers)->withoutVerifying()->get($uri);
            }elseif( strtoupper($method) == 'POST' ){
                if( $body ){
                    return Http::withHeaders($headers)->withoutVerifying()->post($uri);
                }else{
                    return Http::withHeaders($headers)->withoutVerifying()->withBody($body)->post($uri);
                }
                
            }else{
                throw new Error('Metodo HTTP no existe o no está soportado: '.$method);
            }
            
        }else{
            throw new Error('Ärchivo '.'http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json Mal Formado!');
        }
    }
    public function auth (){
        $now = new Carbon();
        $uri = $this::API_BASE_URL.'/v1/auth/apiKey';
        $bearerToken = $this->shopConfigsData['ApiKey'];
        $response = Http::withHeaders([
            'Authorization' => "Bearer $bearerToken",
        ])->withoutVerifying()->post($uri);
        if( $response ){
            $jsonResponse = json_decode($response);
            if( $jsonResponse && property_exists($jsonResponse, 'expiresIn') && property_exists($jsonResponse, 'accessToken') ){
                $fechaExpiracionToken = $now->addSeconds( intval($jsonResponse->expiresIn) )->toIso8601String();
                $accessToken = $jsonResponse->accessToken;
                Storage::put('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json', json_encode(['expirationDate' => $fechaExpiracionToken, 'accessToken' => $accessToken]));
                $this->shopConfigsData['AccessToken'] = $accessToken;
                return;
            }
        }
        throw new Error("Problemas para autenticarse en Paris Marketplace");
    }
    public function getProducts($queryParams = [])
    {
        $uri = $this::API_BASE_URL . '/v2/products/search';
        if( count($queryParams) ){
            $c = 0;
            foreach( $queryParams as $key => $value ){
                if( $c === 0 ){
                    $uri .= "?$key=$value";
                }else{
                    $uri .= "&$key=$value";
                }
                $c++;
            }
        }
        
        $response = $this->makeRequest('GET', $uri, null, []);

        return json_decode( $response );
    }

}



