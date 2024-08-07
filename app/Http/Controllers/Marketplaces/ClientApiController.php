<?php

namespace App\Http\Controllers\Marketplaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use Illuminate\Support\Facades\DB;

class ClientApiController extends Controller
{
    public function send(Request $request){
        $user = $request->user();
        // TODO: usar el shop actual cuando sean multishop las cuentas de usuario
        $shop = $user->shops()->first();
        $marketplaceName = $request->marketplace;
        if( $shop->hasThisMarketplaceByName($marketplaceName) ){
            $action = $request->action;
            if( in_array( $action, Marketplace::getAvailableApiActionsByMarketplaceName($marketplaceName) ) ){
                $marketplace = Marketplace::where('name', $marketplaceName)->first();
                $resolverClassName = "App\\Http\\ApiResolvers\\$marketplace->connector_class_name";
                $apiResolver = new $resolverClassName($marketplace, $shop);
                $data = $apiResolver->{$action}($request->all());
                return response()->json($data);
            }
        }
        
        return response()->json([
            'message' => 'Usuario sin marketplace asociado o marketplace no existe'
        ], 422);
    }

    public function get(Request $request){
        $user = $request->user();
        // TODO: usar el shop actual cuando sean multishop las cuentas de usuario
        $shop = $user->shops()->first();
        $marketplaceName = $request->marketplace;
        if( $shop->hasThisMarketplaceByName($marketplaceName) ){
            $action = $request->action;
            if( in_array( $action, Marketplace::getAvailableApiActionsByMarketplaceName($marketplaceName) ) ){
                $marketplace = Marketplace::where('name', $marketplaceName)->first();
                $resolverClassName = "App\\Http\\ApiResolvers\\$marketplace->connector_class_name";
                $apiResolver = new $resolverClassName($marketplace, $shop);
                $data = $apiResolver->{$action}();
                return response()->json($data);
                
            }
        }
        
        return response()->json([
            'message' => 'Usuario sin marketplace asociado o marketplace no existe'
        ], 422);
    }
}