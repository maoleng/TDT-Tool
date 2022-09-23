<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    private mixed $title;
    private mixed $content;
    private mixed $files;
    private mixed $department;
    private mixed $link;
    private mixed $created_at;

    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->files = $data['files'];
        $this->department = $data['department'];
        $this->link = $data['link'];
        $this->created_at = $data['created_at'];
    }

    public function build(): MailNotification
    {
        return $this
            ->from('napoleon_dai_de@tanthe.com', getConfigValue('mail_notification.sender'))
            ->subject($this->title)
            ->view('mail.mail_notification', [
                'title' => $this->title,
                'content' => $this->content,
                'files' => $this->files,
                'department' => $this->department,
                'link' => $this->link,
                'created_at' => $this->created_at,
            ]);
    }
}
