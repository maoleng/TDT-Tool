<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\View\View as ViewReturn;

class ControlPanel extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Đọc thông báo');
        View::share('route', 'index');
    }

    public function readNotification(): ViewReturn
    {
        return view('app.control_panel.read_notification', [
            'breadcrumb' => 'Đọc thông báo'
        ]);
    }

    public function mailNotification(): ViewReturn
    {
        return view('app.control_panel.mail_notification', [
            'breadcrumb' => 'Nhận'
        ]);
    }

    public function buildSchedule(): ViewReturn
    {
        return view('app.control_panel.build_schedule', [
            'breadcrumb' => 'Nhận'
        ]);
    }
}
