<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudyPlanRequest;
use App\Http\Requests\SetFirstDashWeekRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Models\Config;
use App\Models\Date;
use App\Models\Semester;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function createStudyPlan(?CreateStudyPlanRequest $request, $default = null): RedirectResponse
    {
        if ($request === null) {
            $data = $default;
        } else {
            $data = $request->validated();
        }
        Date::query()->delete();
        $start_date = Carbon::create($data['start_date']);
        $end_date = Carbon::create($data['end_date']);
        $semester_1_start_date = Carbon::create($data['semester_1_start_date']);
        $semester_2_start_date = Carbon::create($data['semester_2_start_date']);
        $semester_1_end_date = Carbon::create($data['semester_2_start_date'])->subDay();
        $semester_2_end_date = Carbon::create($data['semester_3_start_date'])->subDay();

        $semesters = [];
        for ($i = 1; $i <= 3; $i++) {
            $semesters[] = Semester::query()->firstOrCreate(
                [
                    'semester' => $i,
                    'start_year' => $start_date->year,
                    'end_year' => $end_date->year,
                ],
                [
                    'semester' => $i,
                    'start_year' => $start_date->year,
                    'end_year' => $end_date->year,
                ]
            );
        }
        $dates = CarbonPeriod::create($start_date, $end_date);
        $arr = [];
        foreach ($dates as $key => $date) {
            if ($date->between($semester_1_start_date, $semester_1_end_date)) {
                $semester_id = $semesters[0]->id;
            } elseif ($date->between($semester_2_start_date, $semester_2_end_date)) {

                $semester_id = $semesters[1]->id;
            } else {
                $semester_id = $semesters[2]->id;
            }
            $arr[] = [
                'id' => Str::uuid(),
                'date' => $date,
                'week' => (int)ceil(($key + 1) / 7),
                'semester_id' => $semester_id,
            ];
        }
        Date::query()->insert($arr);

        return redirect()->back();
    }

    public function setFirstDashWeek(SetFirstDashWeekRequest $request): RedirectResponse
    {
        Config::query()->where('key', 'first_dash_week')->delete();
        Config::query()->create([
            'key' => 'first_dash_week',
            'value' => $request->get('first_dash_week'),
        ]);

        return redirect()->back();
    }

    public function updatePeriod(UpdatePeriodRequest $request)
    {

    }
}
