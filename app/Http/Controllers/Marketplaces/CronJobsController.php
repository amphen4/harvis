<?php

namespace App\Http\Controllers\Marketplaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use App\Models\MarketplaceApiCronJob;
use Illuminate\Support\Facades\DB;

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
                    'payload' => json_decode($item->payload)
                ];
            }
            return response()->json( $arrayOutput );
        }
    }
}