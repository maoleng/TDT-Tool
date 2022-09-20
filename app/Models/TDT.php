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
    public const OLD_STDPORTAL_URL = 'https://old-stdportal.tdtu.edu.vn';

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

    public function getUnitId($value)
    {
        switch (strtolower($value)) {

            case 'khoa công nghệ thông tin':
                return '5';
            case 'khoa tài chính ngân hàng':
                return 'B';
            case 'trung tâm tin học':
                return 'G';
            case 'phòng cthssv':
            case 'phòng công tác học sinh - sinh viên':
                return 'P02';
            case 'phòng đại học':
                return 'P03';
            case 'phòng sau đại học':
                return 'P04';
            case 'phòng khảo thí và kiểm định chất lượng':
            case 'phòng khảo thí và kđcl':
                return 'P07';
            case 'phòng tài chính':
                return 'P09';
            case 'ban ký túc xá':
                return 'P12';
            case 'tdt creative language center':
                return 'P15';
            case 'trung tâm đào tạo phát triển xã hội (sdtc)':
                return 'P27';
            case 'trung tâm hợp tác doanh nghiệp và cựu sinh viên (ceca)':
            case 'tt hợp tác doanh nghiệp và cựu sinh viên':
                return 'P35';
            case 'viện chính sách kinh tế và kinh doanh (ibep)':
                return 'P48';
        }
    }
}
