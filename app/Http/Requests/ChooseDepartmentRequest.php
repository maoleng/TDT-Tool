<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class ChooseDepartmentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'faculty' => [
                'array',
            ],
            'faculty.*' => [
                'exists:App\Models\Department,id'
            ],
            'popular' => [
                'array',
            ],
            'popular.*' => [
                'exists:App\Models\Department,id'
            ],
            'other' => [
                'array',
            ],
            'other.*' => [
                'exists:App\Models\Department,id'
            ],
            'choose_fast' => [
                Rule::in(['all', 'default', 'delete']),
            ],
        ];

    }
}
