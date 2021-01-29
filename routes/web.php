<?php

use App\Http\Controllers\ReportController;
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

Route::group(['middleware' => 'auth'], function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/reports', ReportController::class . '@index')->name('reports');
    Route::get('/report', ReportController::class . '@show')->name('report');
    Route::get('/store', ReportController::class . '@storeReport')->name('store');
    Route::get('/upload', ReportController::class . '@upload')->name('upload');
    Route::get('/draw', function () {
        return view('canvas');
    });
});

require __DIR__.'/auth.php';

