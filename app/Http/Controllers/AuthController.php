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
        $user = Socialite::driver('google')->user();
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

        return redirect()->route('template.index');

    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        session()->forget('token');
        session()->flush();
        session()->save();

        return redirect()->route('auth.login');
    }
}
