<?php

namespace App\Http\Connectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Marketplace;
use App\Models\Shop;
use Carbon\Carbon;
use Error;
use GuzzleHttp\Client;

class Mercadolibre {

    const API_BASE_URL = "https://api.mercadolibre.com";

    protected $marketplace;
    protected $shop;
    protected $shopConfigsData;

    function __construct(Marketplace $mercadolibre, Shop $shop){
        $this->marketplace = $mercadolibre;
        $this->shop = $shop;
        /** Load Configs */
        $this->shopConfigsData = array();
        foreach( $mercadolibre->marketplaceConfigs()->get() as $marketplaceConfig ){
            $shopMarketplaceConfig =  $marketplaceConfig->shops()->where('shops_id', $this->shop->shops_id)->first();
            if( $shopMarketplaceConfig ){
                $this->shopConfigsData[$marketplaceConfig->name] = json_decode($shopMarketplaceConfig->pivot->value);
            }else{
                throw new Error("El siguiente shop_marketplace_config está perdido: $marketplaceConfig->name");
            }
        }
        if( !Storage::exists('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json') ){
            throw new Error("Debe autenticarse con OAuth en Mercadolibre. ".'https://auth.mercadolibre.cl/authorization?response_type=code&client_id=$APP_ID&redirect_uri=$YOUR_URL&state=$state');
        }
        $file_contents = Storage::get('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json');
        $json = json_decode($file_contents, true);
        if( count($json) ){
            $keys = array_keys($json);
            foreach( $keys as $key ){
                $this->shopConfigsData[$key] = $json[$key];
            }
        }
    }
    public function getShopConfigKey($key){
        return $this->shopConfigsData[$key];
    }
    public function makeRequest($method, $url, $body = null, $headers = []){
        $uri = $this::API_BASE_URL . '/' . $url;
        $method = strtoupper($method);
        if( !Storage::exists('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json') ){
            throw new Error("Debe autenticarse con OAuth en Mercadolibre. ".'https://auth.mercadolibre.cl/authorization?response_type=code&client_id=$APP_ID&redirect_uri=$YOUR_URL&state=$state');
        }
        $file_contents = Storage::get('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json');
        $json = json_decode($file_contents);
        if( $json ){
            $now = new Carbon();
            $fechaExpiracionToken = new Carbon($json->TokenExpiresIn);
            if( $now->greaterThan( $fechaExpiracionToken ) ){
                $this->refreshToken();
            }else{
                $this->shopConfigsData['AccessToken'] = $json->AccessToken;
            }
            $headers = array_merge($headers, [ 
                'Authorization' => 'Bearer '.$this->shopConfigsData['AccessToken'],
                'Accept' => 'application/json'
            ]);
            if( $method == 'POST' || $method == 'PUT' ){
                $headers['Content-Type'] = 'application/json';
            }
            \Log::info( json_encode([
                'method' => $method,
                'uri' => $uri,
                'body' => $body,
                'headers' => $headers
            ]) );
            if( $method == 'GET' ){
                return Http::withHeaders($headers)->withoutVerifying()->get($uri);
            }elseif( $method == 'POST' || $method == 'PUT' ){
                if( $body ){
                    $client = new Client();
                    $response = $client->request($method, $uri, [
                        'headers' => $headers,
                        'body' => $body
                    ]);
                    return $response->getBody()->getContents();
                }else{
                    return Http::withHeaders($headers)->withoutVerifying()->post($uri);
                }
                
            }else{
                throw new Error('Metodo HTTP no existe o no está soportado: '.$method);
            }
            
        }else{
            throw new Error('Archivo '.'http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json Mal Formado!');
        }
    }
    public function refreshToken (){
        $now = new Carbon();
        $uri = $this::API_BASE_URL.'/oauth/token';
        $clientId = $this->shopConfigsData['AppId'];
        $clientSecret = $this->shopConfigsData['SecretKey'];
        $file_contents = Storage::get('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json');
        $json = json_decode($file_contents);
        if( $json ){
            $refreshToken = $json->RefreshToken;
        }
        $response = Http::withHeaders([
            'Content-Type' => "application/x-www-form-urlencoded",
            "Accept" => "application/json"
        ])->asForm()->withoutVerifying()->post($uri, ['grant_type' => 'refresh_token', 'client_id' => $clientId, 'client_secret' => $clientSecret, 'refresh_token' => $refreshToken]);
        if( $response ){
            \Log::info('Refresh token mercadolibre');
            \Log::info($response);
            $jsonResponse = json_decode($response);
            if( $jsonResponse && property_exists($jsonResponse, 'expires_in') && property_exists($jsonResponse, 'access_token') && property_exists($jsonResponse, 'refresh_token') && property_exists($jsonResponse, 'user_id') ){
                $fechaExpiracionToken = $now->addSeconds( intval($jsonResponse->expires_in) )->toIso8601String();
                $accessToken = $jsonResponse->access_token;
                $userId = $jsonResponse->user_id;
                $refreshToken = $jsonResponse->refresh_token;
                Storage::put('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json', json_encode(['TokenExpiresIn' => $fechaExpiracionToken, 'AccessToken' => $accessToken, 'UserId' => $userId, 'RefreshToken' => $refreshToken]));
                $this->shopConfigsData['AccessToken'] = $accessToken;
                return;
            }
        }
        throw new Error("Problemas para refrescar token en Mercadolibre API");
    }
    public function isTokenExpired(){
        if( !Storage::exists('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json') ){
            return false;
        }
        $file_contents = Storage::get('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json');
        $json = json_decode($file_contents);
        if( $json ){
            $now = new Carbon();
            $fechaExpiracionToken = new Carbon($json->TokenExpiresIn);
            if( $now->greaterThan( $fechaExpiracionToken ) ){
                return true;
            }
        }
        return false;
    }
    public function getProducts($queryParams = [])
    {
        return [];
    }

}



