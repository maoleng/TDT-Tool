<?php

use App\Lib\JWT\JWT;
use App\Models\Activity;
use App\Models\Config;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

if (!function_exists('c')) {
    function c(string $key)
    {
        return App::make($key);
    }
}

if (!function_exists('getConfigValue')) {
    function getConfigValue($group_and_key)
    {
        [$group, $key] = explode('.', $group_and_key);
        $config = Config::query()->where('key', $key)->where('group', $group)->first();

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

if (!function_exists('userModel')) {
    function userModel(): Model|Collection|Builder|array|null
    {
        $user_id = authed()->id ?? User::MASTER_ID;

        return User::query()->find($user_id);
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

if (!function_exists('checkCreatedSchedule')) {
    function checkCreatedSchedule(): bool
    {
        return !empty(Session::query()->where('user_id', authed()->id)->where('active', true)->first());
    }
}

if (!function_exists('getSettings')) {
    function getSettings(): Collection|array
    {
        return Setting::query()->where('user_id', User::MASTER_ID)->get();
    }
}

if (! function_exists('activityLog')) {
    function activityLog(string $name, string $description, float $memory, Model $caused_by, Model $performed_on = null)
    {
        $activity = activity($name)->causedBy($caused_by)
            ->withProperties(['memory' => $memory.' MB']);
        $activity = isset($performed_on) ? $activity->performedOn($performed_on) : $activity;
        $activity->log($description);

        $content = '<b>'.(new Activity)->prettyLog($name).'</b>'."\n\n".
            $description."\n".
            now()->format('d-m-Y H:i:s')."\n".
            $memory.' MB';

        (new Client)->post(Activity::TELEGRAM_URL, [
            'verify' => false,
            'headers' => [
                'token' => env('LOG_SECRET_KEY'),
            ],
            'json' => [
                'channel_uuid' => env('LOG_CHANNEL_UUID'),
                'content' => $content
            ],
        ]);
    }


}
