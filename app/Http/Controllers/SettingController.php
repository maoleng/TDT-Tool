<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Support\Facades\View;

class SettingController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Quản lý');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        $settings = Setting::query()->where('user_id', User::MASTER_ID)->get();
        $count_seen_news = Notification::query()->count();

        return view('app.admin.setting', [
            'breadcrumb' => 'Cài đặt cá nhân',
            'settings' => $settings,
            'count_seen_news' => $count_seen_news,
        ]);
    }

    public function toggleAutoReadNotification(): RedirectResponse
    {
        $setting = Setting::query()->where('user_id', User::MASTER_ID)
            ->where('key', 'auto_read_notification')->firstOrFail();
        if ((bool)$setting->value === true) {
            $setting->value = false;
        } else {
            $setting->value = true;
        }
        $setting->save();

        return redirect()->back();
    }

    public function update(SettingRequest $request)
    {
        $data = $request->validated();
        Setting::query()
            ->where('user_id', $data['user_id'])
            ->where('key', $data['key'])
            ->update(['value' => $data['value']]);
    }
}
