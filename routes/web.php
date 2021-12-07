<?php

use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::prefix('/api/messages')->group(function(){
    Route::get('/channel/{channel}', [MessagesController::class, 'get']);
    Route::post('/', [MessagesController::class, 'store']);
    Route::delete('/{message}', [MessagesController::class, 'remove']);
});
