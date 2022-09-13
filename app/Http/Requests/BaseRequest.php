<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => 'Bạn chưa điền :attribute',
            'email' => ':attribute chưa đúng định dạng',
            'same' => ':attribute không khớp',
            'unique' => 'Đã có người chọn :attribute này',
            'exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Địa chỉ email',
            'password' => 'Mật khẩu',
            'retype_password' => 'Mật khẩu nhập lại',
            'device_id' => 'Mã của thiết bị',
        ];
    }

}
