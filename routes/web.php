<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControlPanel;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
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

Route::group(['prefix' => 'app', 'middleware' => [AuthLogin::class]], static function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::group(['as' => 'control_panel.'], static function () {
        Route::get('/read_notification', [ControlPanel::class, 'readNotification'])->name('read_notification');
        Route::get('/mail_notification', [ControlPanel::class, 'mailNotification'])->name('mail_notification');
        Route::get('/build_schedule', [ControlPanel::class, 'buildSchedule'])->name('build_schedule');
    });

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });

});

