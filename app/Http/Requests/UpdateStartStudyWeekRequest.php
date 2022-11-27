<?php

namespace App\Http\Requests;


class UpdateStartStudyWeekRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'semester_1' => [
                'required',
            ],
            'semester_2' => [
                'required',
            ],
            'semester_3' => [
                'required',
            ],

        ];

    }
}
