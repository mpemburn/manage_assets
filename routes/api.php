<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserRolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ServicesController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', AuthController::class . '@login')->name('login');
Route::get('/store', ReportController::class . '@storeReport');

Route::middleware('auth:api')->group( function () {
    Route::post('/service/create', ServicesController::class . '@create');
    Route::put('/service/update/{id}', ServicesController::class . '@update');
    Route::delete('/service/delete/{id}', ServicesController::class . '@delete');
});
