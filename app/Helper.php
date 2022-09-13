<?php

use App\Lib\JWT\JWT;
use App\Models\Config;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

if (!function_exists('c')) {
    function c(string $key)
    {
        return App::make($key);
    }
}

if (!function_exists('getConfig')) {
    function getConfig($key)
    {
        $config = Config::query()->where('key', $key)->first();
        return $config->value ?? null;
    }
}

if (!function_exists('plusOneSystemMail')) {
    function plusOneSystemMail($user): bool
    {
        $max = User::MAX_SYSTEM_MAIL_PER_DAY;
        $current = $user->count_system_mail_daily;
        if ($current >= $max ) {
            return false;
        }
        $user->increment('count_system_mail_daily');
        return true;
    }
}
if (!function_exists('authed')) {
    function authed()
    {
        $token = session()->get('token');
        if (empty($token)) {
            return null;
        }
        return c(JWT::class)->match($token);
    }
}
if (!function_exists('getSettings')) {
    function isDarkMode(): bool
    {
        return !empty(Setting::query()
            ->where('user_id', authed()->id)
            ->where('key', 'theme')
            ->where('value', 'dark')
            ->first());
    }
}

