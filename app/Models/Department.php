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
            '5' => 'Khoa Công Nghệ Thông Tin',
            'B' => 'Khoa Tài Chính Ngân Hàng',
            'G' => 'Trung Tâm Tin Học',
            'P02' => 'Phòng Công Tác Học Sinh - Sinh Viên',
            'P03' => 'Phòng Đại Học',
            'P04' => 'Phòng Sau Đại Học',
            'P07' => 'Phòng Khảo Thí Và Kiểm Định Chất Lượng',
            'P09' => 'Phòng Tài Chính',
            'P12' => 'Ban Ký Túc Xá',
            'P15' => 'TDT Creative Language Center',
            'P27' => 'Trung Tâm Đào Tạo Phát Triển Xã HộI (SDTC)',
            'P35' => 'Trung Tâm Hợp Tác Doanh Nghiệp Và Cựu Sinh Viên',
            'P48' => 'Viện Chính Sách Kinh Tế Và Kinh Doanh (IBEP)',
            'P05' => 'Phòng Điện Toán Và Máy Tính',
            'P06' => 'Phòng Quản Lý Phát Triển Khcn',
            'P08' => 'Phòng Quản Trị Thiết Bị',
            'P10' => 'Phòng Tổ Chức Hành Chính',
            'P11' => 'Phòng Tt',
            'P13' => 'Viện Incos',
            'P14' => 'Viện Incredi',
            'P16' => 'Vp Đảng Ủy Và Công Đoàn',
            'P17' => 'T',
            'P18' => 'Cơ Sở Cà Mau',
            'P19' => 'Cơ Sở Nha Trang',
            'P20' => 'Tổ Tư Vấn Học Đường',
            'P21' => 'Ban Pr',
            'P22' => 'Trung Tâm Tư Vấn Và Kiểm Định Xây Dựng',
            'P26' => 'Trung Tâm Thực Hành',
            'P28' => 'Trung Tâm Việt Nam Học',
            'P29' => 'Trung Tâm Nghiên Cứu Và Đào Tạo Kinh Tế Ứng Dụng',
            'P30' => 'Thư Viện',
            'P31' => 'Trung Tâm An Toàn Lao Động Và Công Nghệ Môi Trường',
            'P32' => 'Trung Tâm Phát Triển Khoa Học Quản Lý Và Công Nghệ Ứng Dụng',
            'P33' => 'Trung Tâm Ứng Dụng Và Phát Triển Mỹ Thuật Công Nghiệp',
            'P34' => 'Trung Tâm Hợp Tác Châu Âu',
            'P36' => 'Ban Quản Lý Dự Án',
            'P37' => 'Cơ Sở Bảo Lộc',
            'P38' => 'Tt Ngoại Ngữ - Tin Học - Bồi Dưỡng Văn Hóa',
            'P39' => 'Trung Tâm Ứng Dụng - Đào Tạo Và Phát Triển Các Giải Pháp Kinh Tế',
            'P42' => 'Trung Tâm Chuyên Gia Hàn Quốc',
            'P43' => 'Trung Tâm Bata',
            'P44' => 'Viện Aimas',
            'P45' => 'Công Ty Tđt',
            'P46' => 'Vfis',
            'P47' => 'Viện Gris',
            'P50' => 'Viện Hợp Tác',
            'A' => 'Khoa Lao Động Công Đoàn',
            'C' => 'Khoa Toán Thống Kê',
            'D' => 'Khoa Khoa Học Thể Thao',
            'E' => 'Khoa Luật',
            'F' => 'Saxion',
            'H' => 'Khoa Dược',
            'I' => 'Khoa Giáo Dục Quốc Tế',
            'K' => 'Trung Tâm Giáo Dục Quốc Phòng - An Ninh',
            '0' => 'Khoa Ngoại Ngữ',
            '1' => 'Khoa Mỹ Thuật Công Nghiệp',
            '2' => 'Khoa Kế Toán',
            '3' => 'Khoa Khoa Học Xã Hội Và Nhân Văn',
            '4' => 'Khoa Điện - Điện Tử',
            '6' => 'Khoa Khoa Học Ứng Dụng',
            '7' => 'Khoa Quản Trị Kinh Doanh',
            '8' => 'Khoa Kỹ Thuật Công Trình',
            '9' => 'Khoa Môi Trường Và Bảo Hộ Lao Động',
            default => 'null',
        };
    }
}
