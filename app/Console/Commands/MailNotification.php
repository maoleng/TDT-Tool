<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailNotificationController;
use App\Http\Controllers\NotificationController;
use App\Jobs\ReadNotification;
use App\Jobs\SendMailNotification;
use App\Models\Notification;
use App\Models\TDT;
use App\Models\User;
use App\Mail\MailNotification as TemplateMailNotification;
use Carbon\Carbon;
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
    public function handle(): void
    {
        $last = Notification::query()->latest()->first();
        $client = (new TDT())->loginAndAuthenticate();
        if ($client === null) {
            throw new \RuntimeException('Lỗi đăng nhập');
        }
        $cookie = $client->getConfig('cookies')->toArray()[3];
        $current_notification_id = $last->notification_id ?? Notification::START_NOTIFICATION_ID;
        $count = 0;
        $seen_notifications = [];
        do {
            $current_notification_id++;
            $response = $client->request('GET', TDT::NEW_DETAIL_URL.'/'.$current_notification_id, [
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
                })->get()->pluck('email', 'id')->toArray();
                $job = new SendMailNotification($template_mail, $user_mails, $data['notification']);
                dispatch($job);

                activity('new_notification')->causedBy(userModel())
                    ->performedOn($data['notification'])
                    ->log('Có thông báo mới: ' . $data['notification']->name);

                $seen_notifications[] = [$current_notification_id, $data['unit_id']];
            }
        } while ($count <= Notification::LIMIT_REQUEST_NOTIFICATION_IF_404);

        (new NotificationController())->autoReadNotification($seen_notifications, $cookie);
    }
}
