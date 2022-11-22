<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class TeacherSurveyRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'password' => [
                'required',
            ],
            'level' => [
                'required',
                Rule::in([34, 45, 35]),
            ],
            'satisfy_text' => [
                'required',
            ],
            'idea_text' => [
                'required',
            ],
        ];

    }
}
