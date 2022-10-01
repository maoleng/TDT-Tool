<?php

namespace App\Http\Requests;


class SetFirstDashWeekRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'first_dash_week' => [
                'required',
            ],
        ];

    }
}
