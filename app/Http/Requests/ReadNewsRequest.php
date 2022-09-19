<?php

namespace App\Http\Requests;


use JetBrains\PhpStorm\ArrayShape;

class ReadNewsRequest extends BaseRequest
{

    #[ArrayShape(['tdt_password' => "string[]", 'code' => "string[]"])]
    public function rules(): array
    {
        return [
            'tdt_password' => [
                'bail',
                'required',
            ],
            'code' => [
                'bail',
                'required',
            ],
        ];

    }
}
