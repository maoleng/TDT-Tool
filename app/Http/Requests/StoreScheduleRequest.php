<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class StoreScheduleRequest extends BaseRequest
{

    #[ArrayShape(['source' => "string[]", 'semester' => "array"])]
    public function rules(): array
    {
        return [
            'source' => [
                'required',
            ],
            'semester' => [
                'required',
                Rule::in(['1', '2', '3']),
            ],
        ];

    }
}
