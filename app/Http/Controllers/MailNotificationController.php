<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChooseDepartmentRequest;
use App\Models\Department;
use App\Models\File;
use App\Models\Notification;
use App\Models\TDT;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class MailNotificationController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function mailNotification(): ViewReturn
    {
        $departments = Department::query()->get();
        $subscribed_departments = Department::query()->whereHas('subscribers', static function ($q) {
            $q->where('user_id', authed()->id);
        })->get()->pluck('id')->toArray();
        $data = [];
        foreach ($departments as $department) {
            if ($department->type === Department::FACULTY) {
                $data[Department::FACULTY][] = $department;
            } elseif ($department->type === Department::POPULAR) {
                $data[Department::POPULAR][] = $department;
            } else {
                $data[Department::OTHER][] = $department;
            }
        }

        return view('app.control_panel.mail_notification', [
            'breadcrumb' => 'Nhận',
            'departments' => $data,
            'subscribed_departments' => $subscribed_departments,
        ]);
    }

    public function chooseDepartment(ChooseDepartmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = User::query()->find(authed()->id);

        if (isset($data['choose_fast'])) {
            if ($data['choose_fast'] === 'all') {
                $department_ids = Department::query()->get()->pluck('id')->toArray();
            } elseif ($data['choose_fast'] === 'delete') {
                $department_ids = [];
            } elseif ($data['choose_fast'] === 'default') {
                $faculty = substr(authed()->student_id, 0, 1);
                $department_ids =Department::query()->where('type', Department::POPULAR)
                    ->get()->pluck('id')->toArray();
                $department_ids[] = Department::query()->where('type', Department::FACULTY)
                    ->where('unit_id', $faculty)->first()->id;
            }
        } else {
            $faculty_ids = $data['faculty'] ?? [];
            $popular_ids = $data['popular'] ?? [];
            $other_ids = $data['other'] ?? [];
            $department_ids = array_merge($faculty_ids, $popular_ids, $other_ids);
        }
        $user->subscribedDepartments()->sync($department_ids);

        return redirect()->back();
    }

    public function chooseOther(Request $request)
    {
        dd($request->all());
    }

    #[ArrayShape([
        'notification' => "\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model",
        'created_at' => "string", 'link' => "string", 'files' => "array",
        'department' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed", 'title' => "string",
        'content' => "mixed", 'unit_id' => "string"
    ])]
    public function getDataForMailNotification($response, $current_notification_id): array
    {
        preg_match('/Ngày đăng [0-9\/]+/u', $response, $match);
        $date = substr($match[0], -10);
        $created_at = 'Ngày ' . $date . ' khoảng ' . Notification::CRON_NOTIFICATION_TIME . ' phút trước';

        preg_match_all('/<a style=\"text-decoration: none;\" href=\"#\" id=\"\d+\" onclick=\"downloadFile\(this.\'[\w\-().]+/', $response, $match);
        if (!empty($match[0])) {
            $files = [];
            foreach ($match[0] as $html_link) {
                preg_match('/id=\"\d+/', $html_link, $match);
                $file_id = substr($match[0], 4);
                preg_match('/this,\'.+/', $html_link, $match);
                $file_name = substr($match[0], 6);
                $file_link = TDT::GET_FILE_URL . "?id=$file_id&filename=$file_name";
                $files[$file_name] = $file_link;
            }
        }
        preg_match('/href=\"\/PhongBan\/ThongBaoPhongBan\?MaDonVi=\w+\">.+<\/a/sU', $response, $match);
        preg_match('/=\w+/', $match[0], $unit_id);
        $unit_id = substr($unit_id[0], 1);
        $department = Department::query()->firstOrCreate(['unit_id' => $unit_id]);
        $department_name = $department->departmentName;

        preg_match('/<h2 class=\"rnews-header\">\r\n.+\r/', $response, $match);
        $title = html_entity_decode(trim(substr($match[0], 27, -1)));
        preg_match('/var tmp = \'.+\'/', $response, $match);
        $content = html_entity_decode(substr($match[0], 11, -1));

        $notification = Notification::query()->create([
            'notification_id' => $current_notification_id,
            'title' => $title,
            'department_id' => $department->id,
            'created_at' => now()->toDateTimeString(),
        ]);

        $content = $this->handleImage($content, $notification);

        return [
            'notification' => $notification,
            'created_at' => $created_at,
            'link' => TDT::NEW_DETAIL_URL . '/' . $current_notification_id,
            'files' => $files ?? [],
            'department' => $department_name,
            'title' => $title,
            'content' => $content,
            'unit_id' => $unit_id,
        ];
    }

    public function handleImage($mail_content, $notification)
    {
        preg_match_all('/data:image\/[A-Za-z-]+;base64.[A-Za-z+\/0-9=]+/', $mail_content, $matches, PREG_OFFSET_CAPTURE);
        $images = $matches[0];
        if (isset($images)) {
            foreach ($images as $image) {
                $content = base64_decode(explode(';base64,', $image[0])[1]);
                $mime = $this->getMimeType($image[0]);
                if (empty($mime)) {
                    throw new \RuntimeException('Sai thể loại ảnh');
                }
                $path = 'notification-' . $notification->notification_id . '/'. Str::random(15) . '.' . $mime;
                Storage::disk('google')->put($path, $content);
                $source = Storage::disk('google')->url($path);
                File::query()->create([
                    'source' => $source,
                    'path' => $path,
                    'size' => strlen($image[0]),
                    'notification_id' => $notification->id,
                ]);
                $mail_content = str_replace($image[0], $source, $mail_content);
            }
        }

        return $mail_content;
    }

    public function getMimeType($base64): ?string
    {
        if (str_starts_with($base64, 'data:image/bmp')) {
            return 'bmp';
        }
        if (str_starts_with($base64, 'data:image/jpeg')) {
            return 'jpg';
        }
        if (str_starts_with($base64, 'data:image/png')) {
            return 'png';
        }
        if (str_starts_with($base64, 'data:image/x-icon')) {
            return 'ico';
        }
        if (str_starts_with($base64, 'data:image/webp')) {
            return 'webp';
        }
        if (str_starts_with($base64, 'data:image/gif')) {
            return 'gif';
        }

        return null;
    }

}
