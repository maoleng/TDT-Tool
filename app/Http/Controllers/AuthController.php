<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('login');
    }

    public function redirect(Request $request): RedirectResponse
    {
        session()->put('device_id', $request->get('device_id'));

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $domain = explode('@', $user->email)[1];
        if ($domain !== 'student.tdtu.edu.vn') {
            return redirect()->back();
        }
        $user = User::query()->updateOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'avatar' => $user->avatar,
                'active' => true,
            ],
        );

        $device = (new DeviceController())->createDevice($user, session()->get('device_id'));
        session()->put('token', $device->token);

        activityLog('login',
            $user->name . ' đã đăng nhập vào hệ thống',
            round(memory_get_usage() / 1000000, 2),
            $user
        );

        return redirect()->route('index');

    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        $user = userModel();

        session()->forget('token');
        session()->flush();
        session()->save();

        activityLog('logout',
            $user->name . ' đã đăng xuất khỏi hệ thống',
            round(memory_get_usage() / 1000000, 2),
            $user,
        );

        return redirect()->route('login');
    }
}
