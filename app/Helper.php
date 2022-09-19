<?php

use App\Lib\JWT\JWT;
use App\Models\Config;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

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

if (!function_exists('getAhrefTagContentPC')) {
    function getAhrefTagContentPC($route): string
    {
        $current_url = URL::current();
        if ($current_url === $route) {
            return "href=$route class=\"side-menu side-menu--active\"";
        }

        return "href=$route class=\"side-menu\"";
    }
}

if (!function_exists('getAhrefTagContentMB')) {
    function getAhrefTagContentMB($route): string
    {
        $current_url = URL::current();
        if ($current_url === $route) {
            return "href=$route class=\"menu side-menu--active\"";
        }

        return "href=$route class=\"menu\"";
    }
}
