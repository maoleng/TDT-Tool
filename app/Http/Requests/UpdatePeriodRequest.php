<?php

namespace App\Http\Requests;


use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class UpdatePeriodRequest extends BaseRequest
{

    #[ArrayShape(['periods' => "string[]", 'periods.*.id' => "string[]"])]
    public function rules(): array
    {
        return [
            'periods' => [
                'required',
            ],
            'periods.*.id' => [
                'exists:App\Models\Period,id',
            ],
        ];

    }

    public function prepareForValidation(): void
    {
        $periods = [];
        foreach ($this->all() as $key => $each) {
            if (str_starts_with($key, 'started_ed') || str_starts_with($key, 'ended_at')) {
                $element = explode('|', $key);
                $time = explode(' ', $each);
                $periods[] = [
                    'id' => $element[1],
                    'started_ed' => substr_replace($time[0], ':', 2, 0) . ':00',
                    'ended_at' => substr_replace($time[1], ':', 2, 0) . ':00',
                ];
            }
        }
        $this->merge([
            'periods' => $periods,
        ]);
    }
}
