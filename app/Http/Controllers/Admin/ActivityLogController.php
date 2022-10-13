<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Support\Facades\View;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Lịch sử hành động');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        $activities = Activity::query()->orderBy('created_at', 'DESC')->paginate(8);

        return view('app.admin.activity_log', [
            'activities' => $activities,
            'breadcrumb' => 'Lịch sử thao tác',
        ]);
    }


}
