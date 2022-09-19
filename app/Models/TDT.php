<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use JsonException;

class TDT extends Base
{
    public const LOGIN_URL = 'https://stdportal.tdtu.edu.vn/Login/SignIn';
    public const AUTH_URL = 'http://sso.tdt.edu.vn/Authenticate.aspx?ReturnUrl=https://studentnews.tdtu.edu.vn';
    public const GET_NEWS_URL = 'https://stdportal.tdtu.edu.vn/home/LayThongBaoMoiSinhVien';
    public const LIST_NEWS_URL = 'https://studentnews.tdtu.edu.vn/Thongbao/TintucList';
    public const SEEN_NEW_URL = 'https://studentnews.tdtu.edu.vn/ThongBao/UpdateDaXem';
    public const NEWS_URL = 'https://studentnews.tdtu.edu.vn';

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function loginAndAuthenticate($student_id, $tdt_password): ?Client
    {
        $client = new Client(['cookies' => true]);
        $login_response = $client->request('POST', self::LOGIN_URL, [
            'form_params' => [
                'user' => $student_id,
                'pass' => $tdt_password,
            ],
        ])->getBody()->getContents();
        $login_response = json_decode($login_response, false, 512, JSON_THROW_ON_ERROR);
        if ($login_response->result === 'fail') {
            return null;
        }
        $token = substr($login_response->url, -8);
        $client->request('GET', self::AUTH_URL, [
            'headers' => [
                'Cookie' => "AUTH_COOKIE=$token|" . now()->addDay()->format('m/d/Y 11:59:00') . ' PM',
            ]
        ]);

        return $client;
    }

    public function getUnitId($value): string
    {
        $faculties = json_decode(json_decode('"[{\"MaDonVi\":\"P12\",\"TenDonVi\":\"Ban ký túc xá\",\"SoTin\":1,\"SoTinChuaXem\":1,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"5\",\"TenDonVi\":\"Khoa Công nghệ thông tin\",\"SoTin\":90,\"SoTinChuaXem\":58,\"LoaiDonVi\":\"KHOA\"},{\"MaDonVi\":\"B\",\"TenDonVi\":\"Khoa Tài chính ngân hàng\",\"SoTin\":7,\"SoTinChuaXem\":6,\"LoaiDonVi\":\"KHOA\"},{\"MaDonVi\":\"P02\",\"TenDonVi\":\"Phòng Công tác học sinh - sinh viên\",\"SoTin\":62,\"SoTinChuaXem\":41,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P03\",\"TenDonVi\":\"Phòng Đại học\",\"SoTin\":223,\"SoTinChuaXem\":176,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P07\",\"TenDonVi\":\"Phòng Khảo thí và KĐCL\",\"SoTin\":9,\"SoTinChuaXem\":4,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P04\",\"TenDonVi\":\"Phòng Sau đại học\",\"SoTin\":2,\"SoTinChuaXem\":1,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P09\",\"TenDonVi\":\"Phòng Tài chính\",\"SoTin\":28,\"SoTinChuaXem\":14,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P15\",\"TenDonVi\":\"TDT Creative Language Center\",\"SoTin\":34,\"SoTinChuaXem\":11,\"LoaiDonVi\":\"KHOA\"},{\"MaDonVi\":\"P35\",\"TenDonVi\":\"TT Hợp tác doanh nghiệp và cựu sinh viên\",\"SoTin\":2,\"SoTinChuaXem\":2,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P27\",\"TenDonVi\":\"Trung tâm đào tạo phát triển xã hội (SDTC)\",\"SoTin\":17,\"SoTinChuaXem\":9,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"G\",\"TenDonVi\":\"Trung tâm tin học\",\"SoTin\":21,\"SoTinChuaXem\":11,\"LoaiDonVi\":\"KHOA\"},{\"MaDonVi\":\"P48\",\"TenDonVi\":\"Viện chính sách kinh tế và kinh doanh (IBEP)\",\"SoTin\":2,\"SoTinChuaXem\":0,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P02\",\"TenDonVi\":\"Phòng CTHSSV\",\"SoTin\":62,\"SoTinChuaXem\":41,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P12\",\"TenDonVi\":\"Ban Ký túc xá\",\"SoTin\":1,\"SoTinChuaXem\":1,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"},{\"MaDonVi\":\"P35\",\"TenDonVi\":\"Trung tâm hợp tác doanh nghiệp và cựu sinh viên (CECA)\",\"SoTin\":2,\"SoTinChuaXem\":2,\"LoaiDonVi\":\"PHONGBAN_TRUNGTAM\"}]"'));
        foreach ($faculties as $faculty) {
            if ($faculty->TenDonVi === $value) {
                return $faculty->MaDonVi;
            }
        }
    }
}
