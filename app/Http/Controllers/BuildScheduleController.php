<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BuildScheduleController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function buildSchedule(): ViewReturn
    {
        return view('app.control_panel.build_schedule', [
            'breadcrumb' => 'Nhận'
        ]);
    }
}
