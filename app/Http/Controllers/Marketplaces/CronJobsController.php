<?php

namespace App\Http\Controllers\Marketplaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use App\Models\MarketplaceApiCronJob;
use Illuminate\Support\Facades\Crypt;

class CronJobsController extends Controller
{
    public function store (Request $request)
    {
        $user = $request->user();
        // TODO: usar el shop actual cuando sean multishop las cuentas de usuario
        $shop = $user->shops()->first();
        $marketplaceName = $request->marketplace;
        if( $shop->hasThisMarketplaceByName($marketplaceName) ){
            $marketplace = Marketplace::where('name', $marketplaceName)->first();
            $apiCronJob = new MarketplaceApiCronJob();
            $apiCronJob->frequency_type = $request->frequencyType;
            $apiCronJob->weekdays = json_encode($request->weekdays);
            $apiCronJob->dayhours = json_encode($request->dayhours);
            $apiCronJob->cron_name = $request->action; // TODO: Verificar seguridad, quizas sea bueno hacer lo mismo que en clienApiController
            $apiCronJob->payload = json_encode([
                'payload' => $request->payload
            ]);
            $apiCronJob->shop()->associate( $shop );
            $apiCronJob->marketplace()->associate( $marketplace );
            $apiCronJob->save();
            return response()->json([
                'message' => 'Configuración programada correctamente'
            ]);
        }
        
    }
    public function index (Request $request)
    {
        $user = $request->user();
        // TODO: usar el shop actual cuando sean multishop las cuentas de usuario
        $shop = $user->shops()->first();
        $marketplaceName = $request->marketplace;
        if( $shop->hasThisMarketplaceByName($marketplaceName) ){
            $marketplace = Marketplace::where('name', $marketplaceName)->first();
            $arrayOutput = [];
            foreach(MarketplaceApiCronJob::where('shop_id', $shop->shops_id)->where('marketplace_id', $marketplace->marketplaces_id)->get() as $item ){
                $arrayOutput[] = [
                    'frequency_type' => $item->frequency_type,
                    'weekdays' => json_decode($item->weekdays),
                    'dayhours' => json_decode($item->dayhours),
                    'payload' => json_decode($item->payload),
                    'id' => Crypt::encryptString( $item->marketplace_api_cron_jobs_id )
                ];
            }
            return response()->json( $arrayOutput );
        }
    }
    public function delete (Request $request)
    {
        if( !$request->id || !MarketplaceApiCronJob::find( Crypt::decryptString($request->id) ) ){
            return response()->json(['message' => 'Error #1. No se pudo realizar la operación.'], 422);
        }
        $user = $request->user();
        // TODO: usar el shop actual cuando sean multishop las cuentas de usuario
        $shop = $user->shops()->first();
        $marketplaceName = $request->marketplace;
        if( $shop->hasThisMarketplaceByName($marketplaceName) ){
            $marketplace = Marketplace::where('name', $marketplaceName)->first();
            $marketplaceApiCronJob = MarketplaceApiCronJob::find( Crypt::decryptString($request->id) );
            if( $marketplaceApiCronJob->marketplace->marketplaces_id == $marketplace->marketplaces_id && $marketplaceApiCronJob->shop->shops_id == $shop->shops_id ){
                $marketplaceApiCronJob->delete();
                return response()->json(['message' => "Operación realizada correctamente"]);
            }
        }
        return response()->json(['message' => 'Error #2. No se pudo realizar la operación.'], 422);
    }
}