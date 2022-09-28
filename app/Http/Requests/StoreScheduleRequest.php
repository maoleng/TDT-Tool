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
            'tdt_password' => [
                'required_without:source',
                function($attribute, $value, $fail) {
                    if (isset($value) && $this->source) {
                        return $fail('Không thể truyền lên mã nguồn và mật khẩu cùng 1 lúc');
                    }
                },
            ],
        ];

    }
}
