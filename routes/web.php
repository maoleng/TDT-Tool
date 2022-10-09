<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\StatisticController;
use App\Http\Middleware\AdminAuthenticate;
use App\Jobs\ReadNotification;
use App\Jobs\SendMailNotification;
use App\Mail\MailNotification;
use App\Mail\MailNotification as TemplateMailNotification;
use App\Models\Config;
use App\Models\Date;
use App\Models\Department;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TDT;
use App\Models\User;
use App\Models\Session as SessionModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\Crypto\Rsa\KeyPair;

use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildScheduleController;
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
            Route::post('/choose_department', [MailNotificationController::class, 'chooseDepartment'])->name('choose_department');
        });
        Route::group(['prefix' => 'build_schedule', 'as' => 'build_schedule.'], static function () {
            Route::get('/', [BuildScheduleController::class, 'buildSchedule'])->name('index');
            Route::post('/store', [BuildScheduleController::class, 'store'])->name('store');
            Route::post('/download', [BuildScheduleController::class, 'downloadSchedule'])->name('download');
        });

    });

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => [AdminAuthenticate::class]], static function () {
        Route::group(['prefix' => 'users', 'as' => 'user.'], static function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::put('/toggle_active/{user}', [UserController::class, 'toggleActive'])->name('update');
        });
        Route::group(['prefix' => 'config', 'as' => 'config.'], static function () {
            Route::get('/', [ConfigController::class, 'index'])->name('index');
            Route::post('/create_study_plan', [ConfigController::class, 'createStudyPlan'])->name('create_study_plan');
            Route::put('/update_period', [ConfigController::class, 'updatePeriod'])->name('update_period');
            Route::put('/update_first_dash_week', [ConfigController::class, 'updateFirstDashWeek'])->name('update_first_dash_week');
        });
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
            Route::put('/auto_read_notification', [SettingController::class, 'toggleAutoReadNotification'])->name('auto_read_notification');
        });
        Route::group(['prefix' => 'statistic', 'as' => 'statistic.'], static function () {
            Route::get('/', [StatisticController::class, 'index'])->name('index');
            Route::get('/mail_sent', [StatisticController::class, 'mailSent'])->name('mail_sent');

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

});

Route::get('/t', function () {
    $a = Carbon::make('2022/09/23 19:00:00')->format('Ymd\THis');
    $a = Carbon::make('20220928T235000Z')->toDateTimeString();
    dd(now());
    dd($a);
});

Route::get('/test123', function () {
    $a = Notification::query()->first();
    $ids = User::query()->get()->pluck('id')->toArray();
//    $a->receivers()->syncWithPivotValues($ids, [
//        'status' => true,
//        'created_at' => now(),
//    ]);
    dd($a->receivers->toArray());
});
