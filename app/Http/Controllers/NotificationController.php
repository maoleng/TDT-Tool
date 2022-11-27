<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReadAllNotificationRequest;
use App\Http\Requests\ReadNewsRequest;
use App\Jobs\ReadNotification;
use App\Models\Notification;
use App\Models\Promotion;
use App\Models\Setting;
use App\Models\TDT;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use JsonException;

class NotificationController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        $promotions = Promotion::query()->where('user_id', authed()->id)->whereNull('status')->get();

        return view('app.control_panel.read_notification', [
            'breadcrumb' => 'Đọc thông báo',
            'promotions' => $promotions,
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function readNews(ReadNewsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Check mật khẩu
        $client = (new TDT())->loginAndAuthenticate(authed()->student_id, $data['tdt_password']);
        if ($client === null) {
            Session::flash('message', 'Sai mật khẩu');
            return redirect()->back();
        }

        // Lấy danh sách thông báo và check empty
        $news = $client->request('GET', TDT::GET_NEWS_URL, [
            'allow_redirects' => [
                'max' => 15,
            ],
        ])->getBody()->getContents();
        $news = json_decode($news, false, 512, JSON_THROW_ON_ERROR);
        $client->request('GET', TDT::NEWS_URL, [
            'allow_redirects' => [
                'max' => 15,
            ],
        ]);
        if (empty($news)) {
            Session::flash('message', 'Đã đọc hết thông báo mới');
            return redirect()->back();
        }

        // Check mã kích hoạt
        $promotion = Promotion::query()->whereNull('status')->where('user_id', authed()->id)
            ->where('id', $data['code'])->first();
        if (isset($promotion)) {
            $promotion->update(['status' => true]);
        } else {
            Session::flash('message', 'Mã kích hoạt không tồn tại hoặc đã hết hạn');
            return redirect()->back();
        }

        // Tiến hành đọc thông báo
        $cookie = $client->getConfig('cookies')->toArray()[3];
        foreach ($news as $key => $new) {
            $seen_url = TDT::SEEN_NEW_URL . "?tinTucID=$new->id&phongBanID=$new->donViQuanLy";
            $job = (new ReadNotification($cookie, $seen_url));
            dispatch($job)->delay(Carbon::now()->addSeconds($key * 5));
        }

        Session::flash('success', [
            'title' => 'Thành công',
            'content' => 'Đang đọc thông báo trong nền, bạn có thể đóng cửa sổ. ',
        ]);

        return redirect()->back();
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function readAll(ReadAllNotificationRequest $request): RedirectResponse
    {
        // Check role và check xem đã dùng chức năng trong hôm nay chưa
        $user = User::query()->where('id', authed()->id)->first();
        if ($user->role === 1) {
            Session::flash('message', 'Bạn không phải người dùng VIP');
            return redirect()->back();
        }
        if ($user->is_read_notification_today) {
            Session::flash('message', 'Đã hết lượt đọc trong hôm nay, vui lòng quay lại vào ngày mai');
            return redirect()->back();
        }
        $user->update(['is_read_notification_today' => true]);

        $tdt_password = $request->get('tdt_password');
        $client = (new TDT())->loginAndAuthenticate(authed()->student_id, $tdt_password);
        $options = [
            'allow_redirects' => [
                'max' => 15,
            ],
        ];

        // Check mật khẩu
        if ($client === null) {
            Session::flash('message', 'Sai mật khẩu');
            return redirect()->back();
        }

        // Check số thông báo chưa đọc
        $response = $client->request('GET', TDT::OLD_STDPORTAL_URL, $options)->getBody()->getContents();
        preg_match('/<div class=\"wrap-square\" style=\"position: relative.+\r\n.+sq-tb.+\r\n.+\r\n.+\r\n.+\r\n.+\r\n.+\d+\r\n.+</', $response, $match);
        if (empty($match)) {
            Session::flash('message', 'Đã đọc hết thông báo mới');
            return redirect()->back();
        }
        preg_match('/\r\n.+\d+\r\n/', $match[0], $match);
        preg_match('/\d+/', $match[0], $count_news);
        if ((int)$count_news[0] < Notification::NOTIFICATION_AT_LEAST_TO_READ) {
            Session::flash('message', 'Số thông báo chưa đọc phải lớn hơn ' . Notification::NOTIFICATION_AT_LEAST_TO_READ);
            return redirect()->back();
        }


        // Lấy danh sách thông báo
        $post_with_faculties = [];
        for ($page = 1; $page <= Notification::MAX_NOTIFICATION_PAGE; $page++) {
            $url = TDT::LIST_NEWS_URL . "?page=$page";
            $response = $client->request('GET', $url, $options)->getBody()->getContents();
            if (str_contains($response, 'Không có kết quả cần tìm') || str_contains($response, 'Data Has Not Been Found')) {
                break;
            }
            $response = html_entity_decode($response);
            preg_match_all('/<a onclick=\"openInNewTab\(\'\/ThongBao\/Detail\/\d{6}\'\)\" class=\"link-detail\" style=\"cursor:pointer!important;\">[A-Za-z ếôá]+<\/a>\r\n(.+\r\n)?\r\n.+\r\n.+<\/i>/u', $response, $matches);
            foreach ($matches[0] as $element) {
                preg_match('/\d{6}/', $element, $post_id);
                preg_match('/<i>[A-Za-zỳọáầảấờễàạằệếýộậốũứĩõúữịỗìềểẩớặòùồợãụủíỹắẫựỉỏừỷởóéửỵẳẹèẽổẵẻỡơôưăêâđĐ\-() ]+/u', $element, $faculty);
                $short_faculty = (new TDT())->getUnitId(substr($faculty[0], 3));
                $post_with_faculties[] = [$post_id[0], $short_faculty];
            }
        }
        // Tiến hành đọc thông báo
        $client->request('GET', TDT::NEWS_URL, $options);
        $cookie = $client->getConfig('cookies')->toArray()[3];
        foreach ($post_with_faculties as $key => $post_with_faculty) {
            $seen = TDT::SEEN_NEW_URL . "?tinTucID=$post_with_faculty[0]&phongBanID=$post_with_faculty[1]";
            $job = (new ReadNotification($cookie, $seen));
            dispatch($job)->delay(Carbon::now()->addSeconds($key * 5));
        }

        Session::flash('success', [
            'title' => 'Thành công',
            'content' => 'Đang đọc thông báo trong nền, bạn có thể đóng cửa sổ. ',
        ]);

        return redirect()->back();
    }

    public function autoReadNotification($seen_notifications, $cookie): void
    {
        $is_auto_read_notification = (bool)Setting::query()->where('user_id', User::MASTER_ID)
            ->where('key', 'auto_read_notification')->firstOrFail()->value;
        if ($is_auto_read_notification === true) {
            foreach ($seen_notifications as $key => $seen_notification) {
                $seen = TDT::SEEN_NEW_URL . "?tinTucID=$seen_notification[0]&phongBanID=$seen_notification[1]";
                $job = (new ReadNotification($cookie, $seen));
                dispatch($job)->delay(Carbon::now()->addSeconds($key * 5));
            }
        }
    }
}
