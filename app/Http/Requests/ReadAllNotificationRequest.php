<?php

namespace App\Http\Requests;

use JetBrains\PhpStorm\ArrayShape;

class ReadAllNotificationRequest extends BaseRequest
{
    #[ArrayShape(['tdt_password' => "string[]"])]
    public function rules(): array
    {
        return [
            'tdt_password' => [
                'bail',
                'required',
            ],
        ];

    }
}
