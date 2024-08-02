<?php

namespace App\Http\Controllers\Marketplaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use Illuminate\Support\Facades\DB;

class ClientApiController extends Controller
{
    public function send(Request $request){
        /*
        $request->validate([
            'hola' => 'required',
            'mundo' => 'required',
        ]);
        */
        return response()->json([
            'message' => 'Hola mundo'
        ]);
    }
}