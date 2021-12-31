<?php

use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ChannelsController;
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

Route::get('/', function(){
    return redirect('/channels');
});

//api routes
Route::prefix('/api/messages')->group(function(){
    Route::get('/channel/{channel}', [MessagesController::class, 'get']);
    Route::post('/', [MessagesController::class, 'store']);
    Route::delete('/{message}', [MessagesController::class, 'remove']);
});

//client routes
Route::prefix('/channels')->group(function(){
    Route::get('/', [ChannelsController::class, 'index']);
    Route::get('/create', [ChannelsController::class, 'create']);
    Route::post('/store', [ChannelsController::class, 'store']);
    Route::get('/leave/channel:{channel}', [ChannelsController::class, 'leave_channel']);
    Route::get('/join/channel:{channel}', [ChannelsController::class, 'join_via_link']);
});
