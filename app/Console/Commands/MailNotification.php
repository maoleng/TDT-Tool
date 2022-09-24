<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailNotificationController;
use App\Jobs\SendMailNotification;
use App\Models\Notification;
use App\Models\TDT;
use App\Models\User;
use App\Mail\MailNotification as TemplateMailNotification;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JsonException;

class MailNotification extends Command
{

    protected $signature = 'mail:notification';
    protected $description = 'Tự động gửi thông báo';


    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function handle()
    {
        $last = Notification::query()->latest()->first();
        $client = (new TDT())->loginAndAuthenticate();
        if ($client === null) {
            throw new \RuntimeException('Lỗi đăng nhập');
        }
        $current_notification_id = $last->notification_id ?? Notification::START_NOTIFICATION_ID;
        $count = 0;
        do {
            $current_notification_id++;
            $response = $client->request('GET', TDT::NEW_DETAIL_URL . '/' . $current_notification_id, [
                'allow_redirects' => [
                    'max' => 30,
                ]
            ])->getBody()->getContents();
            if (str_contains($response, 'Thông báo không tồn tại. Vui lòng kiểm tra lại')) {
                $count++;
            } else {
                $data = (new MailNotificationController())->getDataForMailNotification($response, $current_notification_id);
                $template_mail = new TemplateMailNotification($data);

                $unit_id = $data['unit_id'];
                $user_mails = User::query()->whereHas('subscribedDepartments', static function ($q) use ($unit_id) {
                    $q->where('unit_id', $unit_id);
                })->get()->pluck('email')->toArray();
                $job = new SendMailNotification($template_mail, $user_mails);

                dispatch($job);
            }
        } while ($count <= Notification::LIMIT_REQUEST_NOTIFICATION_IF_404);

    }
}
