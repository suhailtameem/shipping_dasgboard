<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\customerController as Customers;
use App\Http\Controllers\apiController;
use App\Http\Controllers\requestsControllrt;
use App\Http\Controllers\shipmentsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get( '/start', [apiController::class,'appStartup']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([], function () {
    Route::get('/testApi', function () {
        return [
            'status' => '200',
            'message' => 'active',
        ];
    });


    Route::post('/startup', [
        apiController::class,
        'appStartup',
    ]);

    Route::post('/cusLogin', [Customers::class, 'login']);
    Route::post('/ratesCalculator', [apiController::class, 'calculateRates']);
    Route::post('/getCusRequests', [
        apiController::class,
        'getCustomerRequests',
    ]);

    Route::post('/newShippingRequest', [
        requestsControllrt::class,
        'storeApiRequest',
    ]);

    Route::post('/trackingShipment', [
        apiController::class,
        'trackingShipment',
    ]);

    Route::post('/newCustomer', [
        Customers::class,
        'apiSignup',
    ]);

    Route::controller(Customers::class)->group(function () {
        Route::post('/updatePassword', 'changePassword');
        Route::post('/updateBasic', 'updateBasic');
        // Route::post('/newCustomer', 'apiSignup');
        // Route::post('/cusLogin','login');
        // Route::post('/logout','logout');
    });
});
