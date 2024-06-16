<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function getProducts(){
        $user = auth()->user();
        $shop = $user->shops()->first();
        $data = [];

        if( $shop ){
            $data = Product::where('shop_id', $shop->shops_id)
            ->select('sku', 
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(products.name ORDER BY products.name), ',', 1) AS name"),
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(products.price ORDER BY products.name), ',', 1) AS price"),
                DB::raw("GROUP_CONCAT(marketplaces.logo_path ORDER BY products.name) as logos_marketplaces"),
            )
            ->groupBy('sku')
            ->join('marketplaces', 'products.marketplace_id', '=', 'marketplaces.marketplaces_id')
            ->get();
        }

        return response()->json([
            'data' =>  $data, /*[
                ['products_id' => 1, 'stock' => 1, 'name' => 'Nombre Producto']
            ], */
            'status' => 1
        ]);
    }
}
