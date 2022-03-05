<?php

use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('/checkout')->group(function (){
    Route::get('/nests', [CheckoutController::class, 'nests']);
    Route::get('/locations/{egg}', [CheckoutController::class, 'locations']);
    Route::get('/configurations/{egg}/{location}', [CheckoutController::class, 'configurations']);
});



