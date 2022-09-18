<?php

namespace App\Http\Controllers;

use App\Jobs\ReadNotification;
use App\Models\TDT;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function readNotification(): ViewReturn
    {
        return view('app.control_panel.read_notification', [
            'breadcrumb' => 'Đọc thông báo'
        ]);
    }

    
}
