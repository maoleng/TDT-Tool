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
            case 'khoa c??ng ngh??? th??ng tin':
                return '5';
            case 'khoa t??i ch??nh ng??n h??ng':
                return 'B';
            case 'trung t??m tin h???c':
                return 'G';
            case 'ph??ng cthssv':
            case 'ph??ng c??ng t??c h???c sinh - sinh vi??n':
                return 'P02';
            case 'ph??ng ?????i h???c':
                return 'P03';
            case 'ph??ng sau ?????i h???c':
                return 'P04';
            case 'ph??ng kh???o th?? v?? ki???m ?????nh ch???t l?????ng':
            case 'ph??ng kh???o th?? v?? k??cl':
                return 'P07';
            case 'ph??ng t??i ch??nh':
                return 'P09';
            case 'ban k?? t??c x??':
                return 'P12';
            case 'tdt creative language center':
                return 'P15';
            case 'trung t??m ????o t???o ph??t tri???n x?? h???i (sdtc)':
                return 'P27';
            case 'trung t??m h???p t??c doanh nghi???p v?? c???u sinh vi??n (ceca)':
            case 'tt h???p t??c doanh nghi???p v?? c???u sinh vi??n':
                return 'P35';
            case 'vi???n ch??nh s??ch kinh t??? v?? kinh doanh (ibep)':
                return 'P48';
            case 'ph??ng ??i???n to??n v?? m??y t??nh':
                return 'P05';
            case 'ph??ng qu???n l?? ph??t tri???n khcn':
                return 'P06';
            case 'ph??ng qu???n tr??? thi???t b???':
                return 'P08';
            case 'ph??ng t??? ch???c h??nh ch??nh':
                return 'P10';
            case 'ph??ng tt':
                return 'P11';
            case 'vi???n incos':
                return 'P13';
            case 'vi???n incredi':
                return 'P14';
            case 'vp ?????ng ???y v?? c??ng ??o??n':
                return 'P16';
            case 't':
                return 'P17';
            case 'c?? s??? c?? mau':
                return 'P18';
            case 'c?? s??? nha trang':
                return 'P19';
            case 't??? t?? v???n h???c ???????ng':
                return 'P20';
            case 'ban pr':
                return 'P21';
            case 'trung t??m t?? v???n v?? ki???m ?????nh x??y d???ng':
                return 'P22';
            case 'trung t??m th???c h??nh':
                return 'P26';
            case 'trung t??m vi???t nam h???c':
                return 'P28';
            case 'trung t??m nghi??n c???u v?? ????o t???o kinh t??? ???ng d???ng':
                return 'P29';
            case 'th?? vi???n':
                return 'P30';
            case 'trung t??m an to??n lao ?????ng v?? c??ng ngh??? m??i tr?????ng':
                return 'P31';
            case 'trung t??m ph??t tri???n khoa h???c qu???n l?? v?? c??ng ngh??? ???ng d???ng':
                return 'P32';
            case 'trung t??m ???ng d???ng v?? ph??t tri???n m??? thu???t c??ng nghi???p':
                return 'P33';
            case 'trung t??m h???p t??c ch??u ??u':
                return 'P34';
            case 'ban qu???n l?? d??? ??n':
                return 'P36';
            case 'c?? s??? b???o l???c':
                return 'P37';
            case 'tt ngo???i ng??? - tin h???c - b???i d?????ng v??n h??a':
                return 'P38';
            case 'trung t??m ???ng d???ng - ????o t???o v?? ph??t tri???n c??c gi???i ph??p kinh t???':
                return 'P39';
            case 'trung t??m chuy??n gia h??n qu???c':
                return 'P42';
            case 'trung t??m bata':
                return 'P43';
            case 'vi???n aimas':
                return 'P44';
            case 'c??ng ty t??t':
                return 'P45';
            case 'vfis':
                return 'P46';
            case 'vi???n gris':
                return 'P47';
            case 'vi???n h???p t??c':
                return 'P50';
            case 'khoa lao ?????ng c??ng ??o??n':
                return 'A';
            case 'khoa to??n th???ng k??':
                return 'C';
            case 'khoa khoa h???c th??? thao':
                return 'D';
            case 'khoa lu???t':
                return 'E';
            case 'saxion':
                return 'F';
            case 'khoa d?????c':
                return 'H';
            case 'khoa gi??o d???c qu???c t???':
                return 'I';
            case 'trung t??m gi??o d???c qu???c ph??ng - an ninh':
                return 'K';
            case 'khoa ngo???i ng???':
                return '0';
            case 'khoa m??? thu???t c??ng nghi???p':
                return '1';
            case 'khoa k??? to??n':
                return '2';
            case 'khoa khoa h???c x?? h???i v?? nh??n v??n':
                return '3';
            case 'khoa ??i???n - ??i???n t???':
                return '4';
            case 'khoa khoa h???c ???ng d???ng':
                return '6';
            case 'khoa qu???n tr??? kinh doanh':
                return '7';
            case 'khoa k??? thu???t c??ng tr??nh':
                return '8';
            case 'khoa m??i tr?????ng v?? b???o h??? lao ?????ng':
                return '9';
        }
    }
}




















