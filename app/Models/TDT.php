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
    public const AUTH_NEWS_URL = 'http://sso.tdt.edu.vn/Authenticate.aspx?ReturnUrl=https://studentnews.tdtu.edu.vn';
    public const AUTH_SCHEDULE_URL = 'http://sso.tdt.edu.vn/Authenticate.aspx?ReturnUrl=http://thoikhoabieudukien.tdtu.edu.vn';
    public const GET_NEWS_URL = 'https://stdportal.tdtu.edu.vn/home/LayThongBaoMoiSinhVien';
    public const LIST_NEWS_URL = 'https://studentnews.tdtu.edu.vn/Thongbao/TintucList';
    public const SEEN_NEW_URL = 'https://studentnews.tdtu.edu.vn/ThongBao/UpdateDaXem';
    public const NEWS_URL = 'https://studentnews.tdtu.edu.vn';
    public const NEW_DETAIL_URL = 'https://studentnews.tdtu.edu.vn/ThongBao/Detail';
    public const GET_FILE_URL = 'https://studentnews.tdtu.edu.vn/TinTuc/Download';
    public const OLD_STDPORTAL_URL = 'https://old-stdportal.tdtu.edu.vn';

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function loginAndAuthenticate($student_id = null, $tdt_password = null, $url = null): ?Client
    {
        $client = new Client(['cookies' => true]);
        $login_response = $client->request('POST', self::LOGIN_URL, [
            'form_params' => [
                'user' => $student_id ?? env('DEFAULT_USERNAME'),
                'pass' => $tdt_password ?? env('DEFAULT_PASSWORD'),
            ],
        ])->getBody()->getContents();
        $login_response = json_decode($login_response, false, 512, JSON_THROW_ON_ERROR);
        if ($login_response->result === 'fail') {
            return null;
        }
        $token = substr($login_response->url, -8);
        $client->request('GET', $url ?? self::AUTH_NEWS_URL, [
            'headers' => [
                'Cookie' => "AUTH_COOKIE=$token|" . now()->addDay()->format('m/d/Y 11:59:00') . ' PM',
            ]
        ]);

        return $client;
    }

    public function getUnitId($value)
    {
        switch (mb_convert_case($value, MB_CASE_LOWER)) {
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
            case 'phòng điện toán và máy tính':
                return 'P05';
            case 'phòng quản lý phát triển khcn':
                return 'P06';
            case 'phòng quản trị thiết bị':
                return 'P08';
            case 'phòng tổ chức hành chính':
                return 'P10';
            case 'phòng tt':
                return 'P11';
            case 'viện incos':
                return 'P13';
            case 'viện incredi':
                return 'P14';
            case 'vp đảng ủy và công đoàn':
                return 'P16';
            case 't':
                return 'P17';
            case 'cơ sở cà mau':
                return 'P18';
            case 'cơ sở nha trang':
                return 'P19';
            case 'tổ tư vấn học đường':
                return 'P20';
            case 'ban pr':
                return 'P21';
            case 'trung tâm tư vấn và kiểm định xây dựng':
                return 'P22';
            case 'trung tâm thực hành':
                return 'P26';
            case 'trung tâm việt nam học':
                return 'P28';
            case 'trung tâm nghiên cứu và đào tạo kinh tế ứng dụng':
                return 'P29';
            case 'thư viện':
                return 'P30';
            case 'trung tâm an toàn lao động và công nghệ môi trường':
                return 'P31';
            case 'trung tâm phát triển khoa học quản lý và công nghệ ứng dụng':
                return 'P32';
            case 'trung tâm ứng dụng và phát triển mỹ thuật công nghiệp':
                return 'P33';
            case 'trung tâm hợp tác châu âu':
                return 'P34';
            case 'ban quản lý dự án':
                return 'P36';
            case 'cơ sở bảo lộc':
                return 'P37';
            case 'tt ngoại ngữ - tin học - bồi dưỡng văn hóa':
                return 'P38';
            case 'trung tâm ứng dụng - đào tạo và phát triển các giải pháp kinh tế':
                return 'P39';
            case 'trung tâm chuyên gia hàn quốc':
                return 'P42';
            case 'trung tâm bata':
                return 'P43';
            case 'viện aimas':
                return 'P44';
            case 'công ty tđt':
                return 'P45';
            case 'vfis':
                return 'P46';
            case 'viện gris':
                return 'P47';
            case 'viện hợp tác':
                return 'P50';
            case 'khoa lao động công đoàn':
                return 'A';
            case 'khoa toán thống kê':
                return 'C';
            case 'khoa khoa học thể thao':
                return 'D';
            case 'khoa luật':
                return 'E';
            case 'saxion':
                return 'F';
            case 'khoa dược':
                return 'H';
            case 'khoa giáo dục quốc tế':
                return 'I';
            case 'trung tâm giáo dục quốc phòng - an ninh':
                return 'K';
            case 'khoa ngoại ngữ':
                return '0';
            case 'khoa mỹ thuật công nghiệp':
                return '1';
            case 'khoa kế toán':
                return '2';
            case 'khoa khoa học xã hội và nhân văn':
                return '3';
            case 'khoa điện - điện tử':
                return '4';
            case 'khoa khoa học ứng dụng':
                return '6';
            case 'khoa quản trị kinh doanh':
                return '7';
            case 'khoa kỹ thuật công trình':
                return '8';
            case 'khoa môi trường và bảo hộ lao động':
                return '9';
        }
    }
}




















