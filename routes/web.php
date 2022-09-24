<?php

use App\Jobs\SendMailNotification;
use App\Mail\MailNotification;
use App\Models\Department;
use App\Models\Notification;
use App\Models\TDT;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Spatie\Crypto\Rsa\KeyPair;

use App\Http\Controllers\Admin\PromotionController;
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
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

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
            Route::post('/read_all', [NotificationController::class, 'readAll'])->name('read_all');
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
            Route::put('/toggle_active/{user}', [UserController::class, 'toggleActive'])->name('update');
        });
        Route::group(['prefix' => 'promotions', 'as' => 'promotion.'], static function () {Route::put('/toggle_active/{promotion}', [PromotionController::class, 'toggleActive'])->name('update');
        });
    });
});

Route::get('/test', function () {
    $data = 'my secret data';
    $privateKey = PrivateKey::fromString(env('PRIVATE_KEY'));
    $encryptedData = $privateKey->encrypt($data);
    $publicKey = PublicKey::fromString(env('PUBLIC_KEY'));
    $decryptedData = $publicKey->decrypt($encryptedData);
    dd($decryptedData);
});
Route::get('/test123', function () {





});
