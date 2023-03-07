<?php

namespace App\Http\Controllers;

use App\Lib\JWT\JWT;
use App\Models\Device;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DeviceController extends Controller
{
    public function generateToken($user)
    {
        return c(JWT::class)->encode([
            'id' => $user->id,
            'name' => $user->name,
            'student_id' => $user->studentId,
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $user->avatar,
            'active' => $user->active,
        ]);
    }

    public function createDevice($user, $device_id): Builder|Model
    {
        $device = Device::query()->firstOrNew([
            'device_id' => $device_id,
            'user_id' => $user->id,
        ]);
        $device->token = $this->generateToken($user);
        $device->save();

        return $device;
    }

}
