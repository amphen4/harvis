<?php

namespace App\Http\Connectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Marketplace;
use App\Models\Shop;
use Carbon\Carbon;
use Error;

class WalmartCL {

    const API_BASE_URL = "https://marketplace.walmartapis.com";

    protected $marketplace;
    protected $shop;
    protected $shopConfigsData;

    function __construct(Marketplace $walmartCl, Shop $shop){
        $this->marketplace = $walmartCl;
        $this->shop = $shop;
        /** Load Configs */
        $this->shopConfigsData = array();
        foreach( $walmartCl->marketplaceConfigs()->get() as $marketplaceConfig ){
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
            $headers = array_merge($headers, [ 
                'WM_SEC.ACCESS_TOKEN' => $this->shopConfigsData['AccessToken'], 
                'WM_QOS.CORRELATION_ID' => $this->generateUuid4(),
                'Accept' => 'application/json'
            ]);
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
                    return Http::withHeaders($headers)->withoutVerifying()->asForm()->post($uri, $body);
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
        $uri = $this::API_BASE_URL.'/v3/token';
        $clientId = $this->shopConfigsData['clientId'];
        $clientSecret = $this->shopConfigsData['clientSecret'];
        $uuidv4 = $this->generateUuid4();
        $authorization = base64_encode( $clientId.':'.$clientSecret );
        $response = Http::withHeaders([
            'Authorization' => "Basic $authorization",
            "WM_QOS.CORRELATION_ID" => $uuidv4,
            "WM_MARKET" => "cl",
            "WM_SVC.NAME" => "Walmart Marketplace",
            "Accept" => "application/json"
        ])->asForm()->withoutVerifying()->post($uri, ['grant_type' => 'client_credentials']);
        if( $response ){
            $jsonResponse = json_decode($response);
            if( $jsonResponse && property_exists($jsonResponse, 'expires_in') && property_exists($jsonResponse, 'access_token') ){
                $fechaExpiracionToken = $now->addSeconds( intval($jsonResponse->expiresIn) )->toIso8601String();
                $accessToken = $jsonResponse->accessToken;
                Storage::put('http/'.$this->shop->shops_id.'_'.$this->marketplace->marketplaces_id.'.json', json_encode(['expirationDate' => $fechaExpiracionToken, 'accessToken' => $accessToken]));
                $this->shopConfigsData['AccessToken'] = $accessToken;
                return;
            }
        }
        throw new Error("Problemas para autenticarse en Paris Marketplace");
    }
    function generateUuid4() {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    public function getProducts($queryParams = [])
    {
        $uri = $this::API_BASE_URL . '/v3/items';
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



