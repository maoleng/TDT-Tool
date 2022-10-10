<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use JetBrains\PhpStorm\ArrayShape;

class StatisticController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Quản lý');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        $count_seen_news = Notification::query()->whereDate('created_at', now())->count();
        $count_sent_news = NotificationUser::query()->whereHas('notification', static function ($q) {
            $q->whereDate('created_at', now());
        })->count();
        $count_created_schedules = Session::query()->whereDate('created_at', now())->count();

        return view('app.admin.statistic.index', [
            'breadcrumb' => 'Thống kê',
            'count_seen_news' => $count_seen_news,
            'count_sent_news' => $count_sent_news,
            'count_created_schedules' => $count_created_schedules,
        ]);
    }

    #[ArrayShape([
        'mail_by_months' => "array", 'mail_by_days' => "array",
        'count_mail_this_week' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int|mixed",
        'count_mail_this_month' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int|mixed",
        'count_mail_this_year' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int|mixed",
        'count_all' => "int", 'top_users_receive_notification' => "\Illuminate\Database\Eloquent\Builder"
    ])]
    public function mailSent(): array
    {
        $count_mail_this_week = 0;
        $mails_this_week = Notification::query()
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->withCount('receivers')
            ->get();
        foreach ($mails_this_week as $mail_this_week) {
            $count_mail_this_week += $mail_this_week->receivers_count;
        }

        $count_mail_this_month = 0;
        $mails_this_month = Notification::query()
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->withCount('receivers')
            ->get();
        foreach ($mails_this_month as $mail_this_month) {
            $count_mail_this_month += $mail_this_month->receivers_count;
        }

        $count_mail_this_year = 0;
        $mails_this_year = Notification::query()
            ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->withCount('receivers')
            ->get();
        foreach ($mails_this_year as $mail_this_year) {
            $count_mail_this_year += $mail_this_year->receivers_count;
        }

        $count_all = NotificationUser::query()->count();

        $year = Carbon::make(Notification::query()->orderBy('created_at', 'ASC')->first()->created_at)->year;
        $mail_by_months = [];
        for ($i = $year; $i <= now()->year; $i++) {
            $months = Notification::query()->whereYear('created_at', $i)
                ->orderBy('created_at', 'ASC')
                ->get()
                ->groupBy(static function($val) {
                    return Carbon::parse($val->created_at)->format('m');
                });
            for ($j = '1'; $j <= '12'; $j++) {
                if ((int)$j < 10) {
                    $j = '0' . $j;
                }
                if (empty($months[$j])) {
                    $mail_by_months[$i][] = 0;
                } else {
                    $count = 0;
                    foreach ($months[$j] as $model) {
                        $count += $model->countReceivers;
                    }
                    $mail_by_months[$i][] = $count;
                }
            }
        }

        $days_mail = Notification::query()
            ->orderBy('created_at', 'ASC')
            ->get()
            ->groupBy(static function($val) {
                return Carbon::parse($val->created_at)->format('D');
            })
            ->map(static function($models) {
                $count = 0;
                foreach ($models as $model) {
                    $count += $model->countReceivers;
                }
                return $count;
            })
            ->toArray();
        $mail_by_days = [];
        foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day) {
            $mail_by_days[$day] = $days_mail[$day];
        }

        $users = User::query()
                ->withCount('notifications')
                ->orderBy('notifications_count', 'DESC')
                ->limit(5)
                ->get();
        $name_top_users_receive_notification = [];
        $count_mail_top_users_receive_notification = [];
        foreach ($users as $user) {
            $name_top_users_receive_notification[] = $user->name;
            $count_mail_top_users_receive_notification[] = $user->notifications_count;
        }

        return [
            'mail_by_months' => $mail_by_months,
            'mail_by_days' => array_values($mail_by_days),
            'count_mail_this_week' => $count_mail_this_week,
            'count_mail_this_month' => $count_mail_this_month,
            'count_mail_this_year' => $count_mail_this_year,
            'count_all' => $count_all,
            'top_users_receive_notification' => [
                $name_top_users_receive_notification,
                $count_mail_top_users_receive_notification
            ],
        ];
    }

    #[ArrayShape([
        'export_by_months' => "array", 'count_export_this_week' => "int", 'count_export_this_month' => "int",
        'count_export_this_year' => "", 'count_export_all' => "int", 'top_users_export_schedule' => "array[]"
    ])]
    public function scheduleExported(): array
    {
        $count_export_this_week = Session::query()
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
        $count_export_this_month = Session::query()
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
        $count_export_this_year = Session::query()
            ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->count();
        $count_export_all = Session::query()->count();

        $year = Carbon::make(Session::query()->orderBy('created_at', 'ASC')->first()->created_at)->year;
        $export_by_months = [];
        for ($i = $year; $i <= now()->year; $i++) {
            $months = Session::query()->whereYear('created_at', $i)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->selectRaw('MONTH(created_at) as `month`, count(*) as `count`')
                ->get()->pluck('count', 'month')->toArray();
            for ($j = 1; $j <= 12; $j++) {
                $export_by_months[$i][] = empty($months[$j]) ? 0 : $months[$j];
            }
        }

        $users = User::query()
            ->withCount('statisticSessions')
            ->orderBy('statistic_sessions_count', 'DESC')
            ->limit(5)
            ->get();
        $name_top_users_export_schedule = [];
        $count_mail_top_users_export_schedule = [];
        foreach ($users as $user) {
            $name_top_users_export_schedule[] = $user->name;
            $count_mail_top_users_export_schedule[] = $user->statistic_sessions_count;
        }
        return [
            'export_by_months' => $export_by_months,
            'count_export_this_week' => $count_export_this_week,
            'count_export_this_month' => $count_export_this_month,
            'count_export_this_year' => $count_export_this_year,
            'count_export_all' => $count_export_all,
            'top_users_export_schedule' => [
                $name_top_users_export_schedule,
                $count_mail_top_users_export_schedule
            ],
        ];

    }

}
