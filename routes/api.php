<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cLogin;
use App\Http\Controllers\cApp;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
   
});

Route::post('/login_google_token', [cLogin::class, 'login_google_token'] )->name('login_google_token');
Route::post('/logout', [cLogin::class, 'logout'] )->name('logout');
Route::post('/app_list', [cApp::class, 'app_list'])->name('app_list');
Route::post('/app_get_auth', [cApp::class, 'app_get_auth'])->name('app_get_auth');
