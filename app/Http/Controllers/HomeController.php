<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $notifications = Notification::query()->with('department')
            ->orderBy('created_at', 'DESC')
            ->paginate(6);

        return view('app.index', [
            'breadcrumb' => 'Trang chủ',
            'notifications' => $notifications,
        ]);
    }
}
