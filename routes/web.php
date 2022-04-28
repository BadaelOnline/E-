<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Offer\OfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth::routes();

Route::get('/', function (Request $request) {

    return view('welcome');
});

Route::group(['prefix' => 'auth', 'middleware' => ['api']], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('get_user', [AuthController::class, 'get_user']);
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'indexLogin'])->name('index.login');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('register', [AuthController::class, 'indexRegister'])->name('index.register');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');

    Route::get('dashboard', [AuthController::class, 'get_dashboard'])->name('admin.dashboard');
    Route::group(['namespace' => 'Offer', 'prefix' => 'offers'], function () {
        Route::get('/', 'OfferController@index')->name('offers.index');
        Route::get('/getAll', 'OfferController@get')->name('offers.getAll');
        Route::get('/create', 'OfferController@create')->name('offers.create');
        Route::post('/store', 'OfferController@store')->name('offers.store');
    });
    Route::get('stores', function (Request $request) {
        return view('admin.stores.index');
    })->name('stores.list');
});
