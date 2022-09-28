<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class DownloadScheduleRequest extends BaseRequest
{
    #[ArrayShape(['start_at' => "array", 'file_type' => "array"])]
    public function rules(): array
    {
        return [
            'start_at' => [
                'required',
                Rule::in(['now', 'begin']),
            ],
            'file_type' => [
                'required',
                Rule::in(['csv', 'ics']),
            ],
        ];

    }
}
