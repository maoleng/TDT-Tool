<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Base
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'unit_id', 'type',
    ];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_department', 'department_id', 'user_id');
    }

    public function getDepartmentNameAttribute(): string
    {
        return match ($this->unit_id) {
            '5' => 'khoa công nghệ thông tin',
            'B' => 'khoa tài chính ngân hàng',
            'G' => 'trung tâm tin học',
            'P02' => 'phòng công tác học sinh - sinh viên',
            'P03' => 'phòng đại học',
            'P04' => 'phòng sau đại học',
            'P07' => 'phòng khảo thí và kđcl',
            'P09' => 'phòng tài chính',
            'P12' => 'ban ký túc xá',
            'P15' => 'tdt creative language center',
            'P27' => 'trung tâm đào tạo phát triển xã hội (sdtc)',
            'P35' => 'tt hợp tác doanh nghiệp và cựu sinh viên',
            'P48' => 'viện chính sách kinh tế và kinh doanh (ibep)',
            default => 'null',
        };
    }
}
