<?php

use App\Http\Controllers\cHome;
use App\Http\Controllers\cLogin;
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

Route::get('/', [cHome::class, 'index'])->name('home');
/*
Route::get('/login_google_status', [cLogin::class, 'login_google_status'])->name('login_google_status');
Route::get('/login_google_redirect', [cLogin::class, 'login_google_redirect'])->name('login_google_redirect');
Route::get('/login_google_callback', [cLogin::class, 'login_google_callback'])->name('login_google_callback');
*/
//Route::middleware('auth:api')->get('/user' , function () { return 'Info usuario ' . Auth::user()->name . ' (' . Auth::user()->email . ')'; } )->name('api_user');
