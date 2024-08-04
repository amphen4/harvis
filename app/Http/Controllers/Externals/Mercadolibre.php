<?php

namespace App\Http\Controllers\Externals;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class Mercadolibre extends Controller
{
    public function redirect_callback(Request $request){
        $SERVER_GENERATED_AUTHORIZATION_CODE = $request->code ?? false;
        if( !$SERVER_GENERATED_AUTHORIZATION_CODE ){
            $jsonResponse = ['mensaje de error' => $request->error_description ?? 'No se pudo obtener su autorización.'];
            return response()->json($jsonResponse);
        }else{
            $client = new Client();
            $APP_ID = env('TEST_ML_APP_ID');
            $SECRET_KEY = env('TEST_ML_SECRET_KEY');
            $REDIRECT_URI = 'https://tarscom.cl/api/externals/ml/redirect_oauth';
            $response = $client->post('https://api.mercadolibre.com/oauth/token', [
                'headers' => [
                    'accept'       => 'application/json',
                    'content-type' => 'application/x-www-form-urlencoded'
                ],
                'body' => "grant_type=authorization_code&client_id=$APP_ID&client_secret=$SECRET_KEY&code=$SERVER_GENERATED_AUTHORIZATION_CODE&redirect_uri=$REDIRECT_URI"
            ]);
            $json = $response->getBody()->getContents();
            \Log::info('TOKEN from Mercadolibre');
            \Log::info($json);
            $jsonDecoded = json_decode($json, true);
            $shop_id = $request->state;// TODO: Recuperar mensaje enviado para saber el shop id y encriptarlo
            $marketplace = Marketplace::where('Mercadolibre')->first();
            if( !Storage::exists('http/'.$shop_id.'_'.$marketplace->marketplaces_id.'.json') ){
                $array = [];
                $array['AccessToken'] = $jsonDecoded['access_token'];
                $array['TokenExpiresIn'] = (new Carbon)->addSeconds($jsonDecoded['expires_in'])->toIso8601String();
                $array['UserId'] = $jsonDecoded['user_id'];
                $array['RefreshToken'] = $jsonDecoded['refresh_token'];
                Storage::put('http/'.$shop_id.'_'.$marketplace->marketplaces_id.'.json', json_encode($array));
            }else{
                $file_contents = Storage::get('http/'.$shop_id.'_'.$marketplace->marketplaces_id.'.json');
                $array = json_decode($file_contents, true);
                $array['AccessToken'] = $jsonDecoded['access_token'];
                $array['TokenExpiresIn'] = (new Carbon)->addSeconds($jsonDecoded['expires_in'])->toIso8601String();
                $array['UserId'] = $jsonDecoded['user_id'];
                $array['RefreshToken'] = $jsonDecoded['refresh_token'];
                Storage::put('http/'.$shop_id.'_'.$marketplace->marketplaces_id.'.json', json_encode($array));
            }
            return response()->json("Autenticación completada");
        }
    }
    public function notification_callback(Request $request){
        \Log::info('Notification Request from Mercadolibre');
        \Log::info(json_encode($request->input()));
        return response()->json('OK');
    }
}