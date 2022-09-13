<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthLogin;
use App\Http\Middleware\IfAlreadyLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return redirect()->route('login');
});

Route::group(['prefix' => 'auth', 'middleware' => [IfAlreadyLogin::class]], static function() {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/google/redirect', [AuthController::class, 'redirect'])->name('redirect');
    Route::get('/google/callback', [AuthController::class, 'callback'])->name('callback');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => [AuthLogin::class]], static function () {


});

