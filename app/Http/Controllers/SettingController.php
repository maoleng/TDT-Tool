<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    public function update(SettingRequest $request)
    {
        $data = $request->validated();
        Setting::query()->where('user_id', $data['user_id'])->update([
            'key' => $data['key'],
            'value' => $data['value'],
        ]);
    }
}
