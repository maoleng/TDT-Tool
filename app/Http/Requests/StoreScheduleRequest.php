<?php

namespace App\Http\Requests;


use JetBrains\PhpStorm\ArrayShape;

class StoreScheduleRequest extends BaseRequest
{

    #[ArrayShape(['source' => "string[]", 'tdt_password' => "string[]"])]
    public function rules(): array
    {
        return [
            'source' => [
                'required_without:tdt_password',
            ],
        ];

    }
}
