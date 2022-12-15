<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $template_mail;
    private mixed $mails;
    private mixed $user_ids;
    private mixed $notification;

    public function __construct($template_mail, $mails, $notification)
    {
        $this->template_mail = $template_mail;
        $this->mails = $mails;
        $this->user_ids = array_keys($mails);
        $this->notification = $notification;
    }

    public function handle(): void
    {
        try {
            Mail::to(env('DEFAULT_USERNAME') . '@student.tdtu.edu.vn')
                ->bcc($this->mails)
                ->send($this->template_mail);
            $status = true;
        } catch (Exception $e) {
            $status = false;
        }

        $this->notification->receivers()->syncWithPivotValues($this->user_ids, [
            'status' => $status,
        ]);

        activityLog('send_mail_notification',
            'Đã gửi mail thông báo mới cho ' . count($this->mails) . ' bạn',
            round(memory_get_usage() / 1000000, 2),
            userModel(),
            $this->notification
        );
    }
}
