<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadScheduleRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Config;
use App\Models\Date;
use App\Models\Group;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Session as SessionModel;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BuildScheduleController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function buildSchedule(): ViewReturn
    {
        return view('app.control_panel.build_schedule', [
            'breadcrumb' => 'Xếp lịch thời khóa biểu'
        ]);
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (isset($data['source'])) {
            $semester_id = Date::query()->whereDate('date', now())->first()->semester_id;
            DB::beginTransaction();
            try {
                SessionModel::query()->where('user_id', authed()->id)->update(['active' => false]);
                $session = SessionModel::query()->create(['user_id' => authed()->id]);
                $items = $this->pluckItems($data['source']);
                foreach ($items as $item) {
                    $event_data = $this->getEventData($item);
                    $group = $this->createSubjectAndGroup($event_data, $session, $semester_id);
                    $this->syncGroupPeriods($item, $group);
                    $this->getDatesOfSchedule($item, $event_data['day'], $group, $event_data['room']);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Session::flash('message', $e->getMessage());
                return redirect()->back();
            }
        }

        Session::flash('success', [
            'title' => 'Thành công',
            'content' => 'Nhập thời khóa biểu thành công, bạn có thể tải xuống file csv ngay bây giờ',
        ]);
        return redirect()->back();
    }

    public function downloadSchedule(DownloadScheduleRequest $request): StreamedResponse
    {
        $options = $request->validated();
        $groups = User::query()->where('id', authed()->id)
            ->with('sessions.groups.subject')
            ->with('sessions.groups.schedules.date')
            ->with('sessions.groups.periods')
            ->first()->sessions[0]->groups;

        if ($options['file_type'] === 'csv') {
            $calendars = $this->getCalendarCSV($groups, $options);
        } else {
            $calendars = $this->getCalendarICS($groups, $options);
        }

        return $this->download($calendars, $options);
    }

    private function getCalendarICS($groups, $options): array
    {
        $calendars = [];
        $calendars[] = 'BEGIN:VCALENDAR';
        $calendars[] = 'PRODID:-//Google Inc//Google Calendar 70.9054//EN';
        $calendars[] = 'VERSION:2.0';
        $calendars[] = 'CALSCALE:GREGORIAN';
        $calendars[] = 'METHOD:PUBLISH';
        $calendars[] = 'X-WR-CALNAME:' . authed()->student_id . '@student.tdtu.edu.vn';
        $calendars[] = 'X-WR-TIMEZONE:Asia/Ho_Chi_Minh';

        foreach ($groups as $group) {
            foreach ($group->schedules as $schedule) {
                if ($options['start_at'] === 'now') {
                    if ($schedule->date->date->gt(now()->subDay())) {
                        $start_time = $group->periods->where('period', $group->periods->min('period'))->first()->started_ed;
                        $end_time = $group->periods->where('period', $group->periods->max('period'))->first()->ended_at;
                        $start = Carbon::make($schedule->date->date->toDateString() . ' ' . $start_time)->format('Ymd\THis');
                        $end = Carbon::make($schedule->date->date->toDateString() . ' ' . $end_time)->format('Ymd\THis');

                        $calendars[] = 'BEGIN:VEVENT';
                        $calendars[] = 'DTSTART:' . $start;
                        $calendars[] = 'DTEND:' . $end;
                        $calendars[] = 'DESCRIPTION:' .
                            '<b>' .
                            'Phòng: ' . $schedule->room . '<br>' .
                            'Mã môn học: ' . $group->subject->subject_id . '<br>' .
                            'Mã nhóm: ' . $group->group_id .
                            '</b>';
                        $calendars[] = 'SEQUENCE:0';
                        $calendars[] = 'STATUS:CONFIRMED';
                        $calendars[] = 'SUMMARY:' . $group->subject->name;
                        $calendars[] = 'TRANSP:OPAQUE';
                        $calendars[] = 'END:VEVENT';
                    }
                } else {
                    $start_time = $group->periods->where('period', $group->periods->min('period'))->first()->started_ed;
                    $end_time = $group->periods->where('period', $group->periods->max('period'))->first()->ended_at;
                    $start = Carbon::make($schedule->date->date->toDateString() . ' ' . $start_time)->format('Ymd\THis');
                    $end = Carbon::make($schedule->date->date->toDateString() . ' ' . $end_time)->format('Ymd\THis');

                    $calendars[] = 'BEGIN:VEVENT';
                    $calendars[] = 'DTSTART:' . $start;
                    $calendars[] = 'DTEND:' . $end;
                    $calendars[] = 'DESCRIPTION:' .
                        '<b>' .
                        'Phòng: ' . $schedule->room . '<br>' .
                        'Mã môn học: ' . $group->subject->subject_id . '<br>' .
                        'Mã nhóm: ' . $group->group_id .
                        '</b>';
                    $calendars[] = 'SEQUENCE:0';
                    $calendars[] = 'STATUS:CONFIRMED';
                    $calendars[] = 'SUMMARY:' . $group->subject->name;
                    $calendars[] = 'TRANSP:OPAQUE';
                    $calendars[] = 'END:VEVENT';
                }
            }
        }
        $calendars[] = 'END:VCALENDAR';

        return $calendars;
    }

    private function getCalendarCSV($groups, $options): array
    {
        $calendars = [];
        $calendars['head'][] = 'subject';
        $calendars['head'][] = 'start date';
        $calendars['head'][] = 'start time';
        $calendars['head'][] = 'end date';
        $calendars['head'][] = 'end time';
        $calendars['head'][] = 'description';
        foreach ($groups as $group) {
            foreach ($group->schedules as $schedule) {
                if ($options['start_at'] === 'now') {
                    if ($schedule->date->date->gt(now()->subDay())) {
                        $min_period = $group->periods->min('period');
                        $max_period = $group->periods->max('period');
                        $calendars[$schedule->id]['title'] = $group->subject->name;
                        $calendars[$schedule->id]['start_date'] = $schedule->date->date->format('Y/m/d');
                        $calendars[$schedule->id]['start_time'] = $group->periods->where('period', $min_period)->first()->started_ed;
                        $calendars[$schedule->id]['end_date'] = $schedule->date->date->format('Y/m/d');
                        $calendars[$schedule->id]['end_time'] = $group->periods->where('period', $max_period)->first()->ended_at;
                        $calendars[$schedule->id]['description'] =
                            '<b>' .
                            'Phòng: ' . $schedule->room . '<br>' .
                            'Mã môn học: ' . $group->subject->subject_id . '<br>' .
                            'Mã nhóm: ' . $group->group_id .
                            '</b>';
                    }
                } else {
                    $min_period = $group->periods->min('period');
                    $max_period = $group->periods->max('period');
                    $calendars[$schedule->id]['title'] = $group->subject->name;
                    $calendars[$schedule->id]['start_date'] = $schedule->date->date->format('Y/m/d');
                    $calendars[$schedule->id]['start_time'] = $group->periods->where('period', $min_period)->first()->started_ed;
                    $calendars[$schedule->id]['end_date'] = $schedule->date->date->format('Y/m/d');
                    $calendars[$schedule->id]['end_time'] = $group->periods->where('period', $max_period)->first()->ended_at;
                    $calendars[$schedule->id]['description'] =
                        '<b>' .
                        'Phòng: ' . $schedule->room . '<br>' .
                        'Mã môn học: ' . $group->subject->subject_id . '<br>' .
                        'Mã nhóm: ' . $group->group_id .
                        '</b>';
                }
            }
        }

        return $calendars;
    }

    private function download($calendars, $options): StreamedResponse
    {
        $file_name = 'Calendar_of_' . authed()->student_id . '.';
        $file_type = $options['file_type'];
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/' . $file_type,
            'Content-Disposition' => 'attachment; filename=' . $file_name . $file_type,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $callback = function() use ($calendars, $file_type)
        {
            $FH = fopen('php://output', 'wb');
            if ($file_type === 'csv') {
                foreach ($calendars as $row) {
                    fputcsv($FH, $row);
                }
            } else {
                foreach ($calendars as $row) {
                    fwrite($FH, $row . "\n");
                }
            }

            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function createSubjectAndGroup($event_data, $session, $semester_id): Model
    {
        $subject = Subject::query()->firstOrCreate(
            [
                'subject_id' => $event_data['subject_id'],
                'name' => $event_data['subject_name'],
            ],
            [
                'subject_id' => $event_data['subject_id'],
                'name' => $event_data['subject_name'],
            ]
        );

        return Group::query()->firstOrCreate(
            [
                'session_id' => $session->id,
                'group_id' => $event_data['group_id'],
                'subject_id' => $subject->id,
            ],
            [
                'group_id' => $event_data['group_id'],
                'semester_id' => $semester_id,
                'subject_id' => $subject->id,
                'session_id' => $session->id,
            ]
        );
    }

    /**
     * @throws \JsonException
     */
    private function getDatesOfSchedule($item, $day_of_week, $group, $room): void
    {
        preg_match('/Week:.+</', $item, $match);
        preg_match('/[\d-]+/', substr($match[0], 14), $match);
        $first_dash_week = Config::query()->where('key', 'first_dash_week')->first()->value;
        $cur_week = $first_dash_week;
        $weeks = mb_str_split($match[0]);
        $new_weeks = [];
        foreach ($weeks as $key => $week) {
            if ($key !== 0) {
                $cur_week++;
            }
            if ($week !== '-') {
                $new_weeks[] = $cur_week;
            }
        }

        $semester_ids_string = Config::query()->where('key', 'current_semester_ids')->first()->value;
        $semester_ids = json_decode($semester_ids_string, false, 512, JSON_THROW_ON_ERROR);

        $date_ids = Date::query()
            ->whereIn('semester_id', $semester_ids)
            ->whereRaw('WEEKDAY(date) = ' . $day_of_week)
            ->whereIn('week', $new_weeks)
            ->orderBy('week', 'ASC')->get()->pluck('id')->toArray();
        foreach ($date_ids as $date_id) {
            Schedule::query()->firstOrCreate(
                [
                    'date_id' => $date_id,
                    'group_id' => $group->id,
                ],
                [
                    'date_id' => $date_id,
                    'group_id' => $group->id,
                    'room' => $room,
                ],
            );
        }
    }

    private function syncGroupPeriods($item, $group): void
    {
        preg_match('/Period: .+\d+ /', $item, $match);
        preg_match_all('/\d+/', $match[0], $match);
        $periods = range($match[0][0], $match[0][1]);
        $period_ids = Period::query()->whereIn('period', $periods)->get()->pluck('id')->toArray();
        $group->periods()->sync($period_ids);
    }

    #[ArrayShape([
        'room' => "string", 'day' => "mixed", 'subject_id' => "string", 'subject_name' => "string",
        'group_id' => "mixed"
    ])]
    private function getEventData($item): array
    {
        preg_match('/Room:.+\)/', $item, $match);
        preg_match('/>[\w_ -]+/', $match[0], $match);
        $room = substr($match[0] ?? 'nnull', 1);
        $day = $item[strlen($item) - 1];
        preg_match('/\(\w{6} /', $item, $match);
        $subject_id = substr($match[0], 1, 6);
        preg_match('/b>[\wỳọáầảấờễàạằệếýộậốũứĩõúữịỗìềểẩớặòùồợãụủíỹắẫựỉỏừỷởóéửỵẳẹèẽổẵẻỡơôưăêâđĐ -]+/u', $item, $match);
        $subject_name = substr($match[0], 2);
        preg_match('/Group:.+\d+\)/U', $item, $match);
        preg_match('/\d+/', $match[0], $match);
        $group_id = $match[0];

        return [
            'room' => $room,
            'day' => $day,
            'subject_id' => $subject_id,
            'subject_name' => $subject_name,
            'group_id' => $group_id,
        ];
    }

    private function pluckItems($source): array
    {
        $raw_html = html_entity_decode($source);
        preg_match_all('/<tr id="ThoiKhoaBieu1_row\d" class="rowContent">\r\n.+\r\n.+tr>/U', $raw_html, $matches);
        $elements = $matches[0];
        $items = [];
        foreach ($elements as $element) {
            preg_match_all('/<td id="ThoiKhoaBieu1_row[\d]col\d" class="cell".+td>/U', $element, $matches);
            $periods = $matches[0];
            foreach ($periods as $key => $period) {
                preg_match_all('/<p style=\'padding:0px 0px 10px 0px; margi.+p>/U', $period, $matches);
                foreach ($matches[0] as $item) {
                    $items[] = $item.$key;
                }
            }
        }

        return $items;
    }
}
