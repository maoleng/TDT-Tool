<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReadNewsRequest;
use App\Jobs\ReadNotification;
use App\Models\Promotion;
use App\Models\TDT;
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

    public function readNotification(): ViewReturn
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
}
