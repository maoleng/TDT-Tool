<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\View\View as ViewReturn;

class UserController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Quản lý');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        return view('app.admin.user.index', [
            'breadcrumb' => 'Người dùng'
        ]);
    }


}
