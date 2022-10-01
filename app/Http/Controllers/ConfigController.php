<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudyPlanRequest;
use App\Http\Requests\SetFirstDashWeekRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Models\Config;
use App\Models\Date;
use App\Models\Period;
use App\Models\Semester;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Quản lý');
        View::share('route', 'index');
    }

    /**
     * @throws \JsonException
     */
    public function index(): ViewReturn
    {
        $periods = Period::query()->orderBy('period')->get()->map(static function ($period) {
            $start_time = explode(':', $period->started_ed);
            $end_time = explode(':', $period->ended_at);
            return [
                'id' => $period->id,
                'period' => $period->period,
                'started_ed' => $start_time[0] . $start_time[1],
                'ended_at' => $end_time[0] . $end_time[1],
            ];
        });
        $first_dash_week = Config::query()->where('key', 'first_dash_week')->first()->value;

        $semester_id = Date::query()->whereDate('date', now())->first()->semester_id;
        $semester = Semester::query()->find($semester_id);

        $semester_ids_string = Config::query()->where('key', 'current_semester_ids')->first()->value;
        $semester_ids = json_decode($semester_ids_string, false, 512, JSON_THROW_ON_ERROR);
        $first_date = Date::query()->whereIn('semester_id', $semester_ids)->orderBy('date', 'ASC')->first()->date->format('d/m/Y');
        $last_date = Date::query()->whereIn('semester_id', $semester_ids)->orderBy('date', 'DESC')->first()->date->format('d/m/Y');
        $first_date_semester_1 = Date::query()->where('semester_id', $semester_ids[0])->first()->date->format('d/m/Y');
        $first_date_semester_2 = Date::query()->where('semester_id', $semester_ids[1])->first()->date->format('d/m/Y');
        $first_date_semester_3 = Date::query()->where('semester_id', $semester_ids[2])->first()->date->format('d/m/Y');

        return view('app.admin.config_time', [
            'breadcrumb' => 'Cấu hình thời gian cho năm học ' . now()->year,
            'periods' => $periods,
            'first_dash_week' => $first_dash_week,
            'semester' => $semester,
            'first_date' => $first_date,
            'last_date' => $last_date,
            'first_date_semester_1' => $first_date_semester_1,
            'first_date_semester_2' => $first_date_semester_2,
            'first_date_semester_3' => $first_date_semester_3,
        ]);
    }

    public function createStudyPlan(?CreateStudyPlanRequest $request, $default = null): RedirectResponse
    {
        if ($request === null) {
            $data = $default;
        } else {
            $data = $request->validated();
        }
        $start_date = Carbon::create($data['start_date']);
        $end_date = Carbon::create($data['end_date']);
        $semester_1_start_date = Carbon::create($data['semester_1_start_date']);
        $semester_2_start_date = Carbon::create($data['semester_2_start_date']);
        $semester_1_end_date = Carbon::create($data['semester_2_start_date'])->subDay();
        $semester_2_end_date = Carbon::create($data['semester_3_start_date'])->subDay();

        DB::beginTransaction();
        try {
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
            $semester_ids = json_encode(array_column($semesters, 'id'), JSON_THROW_ON_ERROR);
            Config::query()->updateOrCreate(
                [
                    'key' => 'current_semester_ids',
                ],
                [
                    'key' => 'current_semester_ids',
                    'value' => $semester_ids
                ]
            );

            $dates = CarbonPeriod::create($start_date, $end_date);
            foreach ($dates as $key => $date) {
                if ($date->between($semester_1_start_date, $semester_1_end_date)) {
                    $semester_id = $semesters[0]->id;
                } elseif ($date->between($semester_2_start_date, $semester_2_end_date)) {

                    $semester_id = $semesters[1]->id;
                } else {
                    $semester_id = $semesters[2]->id;
                }
                Date::query()->firstOrCreate(
                    [
                        'date' => $date,
                    ],
                    [
                        'date' => $date,
                        'week' => (int)ceil(($key + 1) / 7),
                        'semester_id' => $semester_id,
                    ]
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('message', $e->getMessage());

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function updateFirstDashWeek(SetFirstDashWeekRequest $request): RedirectResponse
    {
        Config::query()->where('key', 'first_dash_week')->delete();
        Config::query()->create([
            'key' => 'first_dash_week',
            'value' => $request->get('first_dash_week'),
        ]);

        return redirect()->back();
    }

    public function updatePeriod(UpdatePeriodRequest $request): RedirectResponse
    {
        $periods = $request->validated()['periods'];
        foreach ($periods as $period) {
            Period::query()->where('id', $period['id'])->update([
                'started_ed' => $period['started_ed'],
                'ended_at' => $period['ended_at'],
            ]);
        }

        return redirect()->back();
    }
}
