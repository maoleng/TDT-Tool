<?php

namespace App\Jobs;

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

    public function __construct($template_mail, $mails)
    {
        $this->template_mail = $template_mail;
        $this->mails = $mails;
    }

    public function handle(): void
    {
        Mail::to(env('DEFAULT_USERNAME') . '@student.tdtu.edu.vn')
            ->bcc($this->mails)
            ->send($this->template_mail);
    }
}
