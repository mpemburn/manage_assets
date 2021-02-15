<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserRolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryController;
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
    Route::post('/receive_files', ReportController::class . '@receive')->name('receive');
    Route::post('/receive_inventory', InventoryController::class . '@receive');

    Route::post('/roles/create', RolesController::class . '@create');
    Route::put('/roles/update', RolesController::class . '@update');
    Route::delete('/roles/delete', RolesController::class . '@delete');

    Route::post('/permissions/create', PermissionsController::class . '@create');
    Route::put('/permissions/update', PermissionsController::class . '@update');
    Route::delete('/permissions/delete', PermissionsController::class . '@delete');

    Route::post('/user_roles', UserRolesController::class . '@edit');
});
