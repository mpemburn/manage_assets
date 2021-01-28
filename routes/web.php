<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SheetsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api_auth', SheetsController::class . '@show');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/reports', ReportController::class . '@index')->name('reports');
    Route::get('/report', ReportController::class . '@show')->name('report');
    Route::get('/upload', ReportController::class . '@upload')->name('upload');
    Route::get('/draw', AssetController::class . '@show');
});

require __DIR__.'/auth.php';

