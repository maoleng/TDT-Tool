<?php

namespace App\Http\Requests;


use JetBrains\PhpStorm\ArrayShape;

class CreateStudyPlanRequest extends BaseRequest
{

    #[ArrayShape([
        'start_date' => "string[]", 'end_date' => "string[]", 'semester_1_start_date' => "string[]",
        'semester_2_start_date' => "string[]", 'semester_3_start_date' => "string[]"
    ])]
    public function rules(): array
    {
        return [
            'start_date' => [
                'required',
            ],
            'end_date' => [
                'required',
            ],
            'semester_1_start_date' => [
                'required',
            ],
            'semester_2_start_date' => [
                'required',
            ],
            'semester_3_start_date' => [
                'required',
            ],

        ];
    }

    public function prepareForValidation(): void
    {
        $data = $this->all();
        $arr_start_date = explode('/', $data['start_date']);
        $arr_end_date = explode('/', $data['end_date']);
        $arr_semester_1_start_date = explode('/', $data['semester_1_start_date']);
        $arr_semester_2_start_date = explode('/', $data['semester_2_start_date']);
        $arr_semester_3_start_date = explode('/', $data['semester_3_start_date']);

        $this->merge([
            'start_date' => $arr_start_date[2] . '/' . $arr_start_date[1] . '/' . $arr_start_date[0],
            'end_date' => $arr_end_date[2] . '/' . $arr_end_date[1] . '/' . $arr_end_date[0],
            'semester_1_start_date' => $arr_semester_1_start_date[2] . '/' . $arr_semester_1_start_date[1] . '/' . $arr_semester_1_start_date[0],
            'semester_2_start_date' => $arr_semester_2_start_date[2] . '/' . $arr_semester_2_start_date[1] . '/' . $arr_semester_2_start_date[0],
            'semester_3_start_date' => $arr_semester_3_start_date[2] . '/' . $arr_semester_3_start_date[1] . '/' . $arr_semester_3_start_date[0],
        ]);
    }

}
