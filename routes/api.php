<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewwAuthController;
use App\Http\Controllers\Api\PaystackWebhookController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/log', [NewwAuthController::class, 'loginUserr']);
Route::get('/user', [NewwAuthController::class, 'userData'])->middleware('auth:sanctum');
Route::post('/logout', [NewwAuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
Route::get('/check-token', [NewwAuthController::class, 'checkToken'])->middleware('auth:sanctum');

Route::controller(PaystackWebhookController::class)->group(function () {
            
        Route::post('/paystack_flaretech/webhook','handle');
});