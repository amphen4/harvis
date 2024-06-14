<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function getProducts(){

        return response()->json([
            'data' => [
                ['stock' => 1, 'name' => 'Nombre Producto']
            ],
            'status' => 1
        ]);
    }
}
