<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Support\Facades\View;

class StatisticController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Quản lý');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        $count_seen_news = Notification::query()->count();

        return view('app.admin.statistic', [
            'breadcrumb' => 'Thống kê',
            'count_seen_news' => $count_seen_news,
        ]);
    }

}
