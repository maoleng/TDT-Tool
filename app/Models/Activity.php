<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;
use Spatie\Activitylog\Models\Activity as OriginalActivity;

class Activity extends OriginalActivity
{
    use HasFactory;
    public $timestamps = false;

    public const TELEGRAM_URL = 'https://maolengram.skrt.cc/api/message';

    protected $fillable = [
        'group', 'key', 'value',
    ];

    #[Pure]
    public function getLogAttribute(): ?string
    {
        return $this->prettyLog($this->log_name);
    }

    public function prettyLog($log): ?string
    {
        return match ($log) {
            'statistic_mail' => 'Thống kê mail',
            'statistic_build_schedule' => 'Thống kê xếp lịch',
            'export_schedule' => 'Xuất lịch',
            'import_schedule' => 'Nhập lịch',
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'survey_teacher' => 'Đánh giá giảng viên',
            'send_mail_notification' => 'Hệ thống gửi mail',
            'new_notification' => 'Thông báo mới',
            default => null,
        };
    }

    public function getCreatedAtAttribute($date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y H:i:s');
    }

}
