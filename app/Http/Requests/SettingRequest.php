<?php

namespace App\Http\Requests;


class SettingRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'exists:App\Models\User,id',
            ],
            'key' => [
                'required',
            ],
            'value' => [
                'required',
            ],
        ];

    }
}
