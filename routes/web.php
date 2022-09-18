<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildScheduleController;
use App\Http\Controllers\ControlPanel;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailNotificationController;
use App\Http\Controllers\NotificationController;
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
        Route::group(['prefix' => 'read_notification', 'as' => 'read_notification.'], static function () {
            Route::get('/', [NotificationController::class, 'readNotification'])->name('index');
            Route::post('/read_news', [NotificationController::class, 'readNews'])->name('read_news');
        });
        Route::group(['prefix' => 'mail_notification', 'as' => 'mail_notification.'], static function () {
            Route::get('/', [MailNotificationController::class, 'mailNotification'])->name('index');

        });
        Route::group(['prefix' => 'build_schedule', 'as' => 'build_schedule.'], static function () {
            Route::get('/', [BuildScheduleController::class, 'buildSchedule'])->name('index');
        });

    });

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], static function () {
        Route::group(['prefix' => 'users', 'as' => 'user.'], static function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
        });
    });
});

Route::get('/test', [NotificationController::class, 'readNews']);
