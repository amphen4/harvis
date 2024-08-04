<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Stores\ProductsController;
use App\Http\Controllers\Marketplaces\ClientApiController;
use App\Http\Controllers\Externals\Mercadolibre;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', function (Request $request) {
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response(['message' => 'Sus credenciales ingresadas son incorrectas o no existen'], 422);
    }
    $expirationTime = now()->addHours(6);
    $user = auth()->user();
    $token = $user->createToken('client', ['store-account'], $expirationTime);
    return ['token' => $token->plainTextToken, 'expiration_date' => $expirationTime->toISOString(), 'name' => $user->name];
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/products', [ProductsController::class, 'getProducts']);
    Route::post('/marketplaces/client_api', [ClientApiController::class, 'send']);
});

Route::get('externals/ml/redirect_oauth', [Mercadolibre::class, 'redirect_callback']);
Route::post('externals/ml/notification_callback', [Mercadolibre::class, 'notification_callback']);

