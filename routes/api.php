<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Stores\ProductsController;
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
        return response(['message' => __('auth.failed')], 422);
    }
    $expirationTime = now()->addHours(6);
    $token = auth()->user()->createToken('client', ['store-account'], $expirationTime);
    return ['token' => $token->plainTextToken, 'expiration_date' => $expirationTime->toISOString()];
});

Route::middleware('auth:sanctum')->group(function(){

    Route::get('/products', [ProductsController::class, 'getProducts']);

});

