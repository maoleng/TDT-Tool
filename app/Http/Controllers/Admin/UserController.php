<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $users = User::query()
            ->with('promotions')
            ->withCount(['promotions' => function ($q) {
                $q->whereNull('status');
            }])
            ->orderBy('created_at', 'DESC')
            ->paginate(6);
//        dd($users);
        return view('app.admin.user.index', [
            'breadcrumb' => 'Người dùng',
            'users' => $users,
        ]);
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if (!$user->active) {
            $user->active = true;
        } else {
            $user->active = false;
        }
        $user->save();

        return redirect()->back();

    }


}
