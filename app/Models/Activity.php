<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as OriginalActivity;

class Activity extends OriginalActivity
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'group', 'key', 'value',
    ];

    public function getLogAttribute(): ?string
    {
        return match ($this->log_name) {
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
