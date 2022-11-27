<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherSurveyRequest;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\View\View as ViewReturn;

class TeacherSurveyController extends Controller
{

    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function index(): ViewReturn
    {
        return view('app.control_panel.teacher_survey', [
            'breadcrumb' => 'Đánh giá chất lượng giảng viên',
        ]);
    }

    public function survey(TeacherSurveyRequest $request)
    {
        $data = $request->validated();
        $user = userModel();
        $student_card_id = strtoupper($user->student_id);
        $arr_level = str_split($data['level']);
        $rand_start_level = (int) $arr_level[0];
        $rand_end_level = (int) $arr_level[1];
        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $login_response = $client->request('POST', 'https://teaching-quality-survey.tdtu.edu.vn/stdlogin.aspx', [
            'form_params' => [
                '__VIEWSTATE' => '/wEPDwUKMjAyMzUyMTE1NA9kFgICAw9kFgICCA8PFgIeBFRleHRlZGRkpnb1jEyhDIWIAOsI5zZIHCi1uztfFxT4kF1KLOx554E=',
                '__VIEWSTATEGENERATOR' => '6B6E290B',
                '__EVENTVALIDATION' => '/wEdAAR7VB2dSTTP30M8X0QS2aG5DFTzKcXJqLg+OeJ6QAEa2kPTPkdPWl+8YN2NtDCtxieinihG6d/Xh3PZm3b5AoMQf5CsxmWAELckXFjG+lQp3P3I3eGl9Ea5EezMVg8qBF4=',
                'txtUser' => $student_card_id,
                'txtPass' => $data['password'],
                'btnLogin' => 'Đăng+nhập',
            ],
        ])->getBody()->getContents();
        if (str_contains($login_response, 'Đăng nhập thất bại')) {
            Session::flash('message', 'Sai mật khẩu');
            return redirect()->back();
        }

        try {
            preg_match('/Token=.*&/', $login_response, $match);
            $token = substr($match[0], 6, 8);
            $all_survey = $client->request('GET', 'https://teaching-quality-survey.tdtu.edu.vn/choosesurvey.aspx?&Token='.$token)->getBody()->getContents();
            preg_match('/id="__VIEWSTATE" value=".*"/', $all_survey, $view_state_match);
            $view_state = substr($view_state_match[0], 24, -1);
            preg_match('/id="__VIEWSTATEGENERATOR" value=".*"/', $all_survey, $view_state_generator_match);
            $view_state_generator = substr($view_state_generator_match[0], 33, -1);
            preg_match_all("/$student_card_id/", $all_survey, $student_card_id_match);
            $count = count($student_card_id_match[0]);
            if ($count === 0 ) {
                Session::flash('message', 'Có lỗi ở $count, vui lòng lói cho Leng để fix');
                return redirect()->back();
            }
            for ($i = 0; $i <= $count; $i++) {
                $each_survey = $client->request('POST', 'https://teaching-quality-survey.tdtu.edu.vn/choosesurvey.aspx?Token='.$token, [
                    'form_params' => [
                        '__EVENTTARGET' => 'gvMonHoc',
                        '__EVENTARGUMENT' => 'Select$'.$i,
                        '__VIEWSTATE' => $view_state,
                        '__VIEWSTATEGENERATOR' => $view_state_generator,
                    ],
                ])->getBody()->getContents();
                if ($i === 0) {
                    preg_match('/id="__VIEWSTATE" value=".*"/', $each_survey, $view_state_match);
                    $view_state_survey = substr($view_state_match[0], 24, -1);
                    preg_match('/id="__VIEWSTATEGENERATOR" value=".*"/', $each_survey, $view_state_generator_match);
                    $view_state_generator = substr($view_state_generator_match[0], 33, -1);
                    preg_match('/id="__EVENTVALIDATION" value=".*"/', $each_survey, $event_validation_match);
                    $event_validation = substr($event_validation_match[0], 30, -1);
                }

                $client->request('POST', 'https://teaching-quality-survey.tdtu.edu.vn/Survey.aspx', [
                    'form_params' => [
                        '__EVENTTARGET' => '',
                        '__EVENTARGUMENT' => '',
                        '__VIEWSTATE' => $view_state_survey,
                        '__VIEWSTATEGENERATOR' => $view_state_generator,
                        '__EVENTVALIDATION' => $event_validation,
                        'gv1$ctl02$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv1$ctl03$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv1$ctl04$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv2$ctl02$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv2$ctl03$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv2$ctl04$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl02$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl03$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl04$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl05$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl06$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl07$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv3$ctl08$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv4$ctl02$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv4$ctl03$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv5$ctl02$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv5$ctl03$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv5$ctl04$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv5$ctl05$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv5$ctl06$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'gv7$ctl02$group1' => 'rd'.random_int($rand_start_level, $rand_end_level),
                        'txt1' => $data['satisfy_text'],
                        'txt2' => $data['idea_text'],
                        'btnTiepTuc.x' => '30',
                        'btnTiepTuc.y' => '3',
                    ]
                ])->getBody()->getContents();
            }
        } catch (Exception $e) {
            Session::flash('message', '==='.$e->getMessage().' at line '.$e->getLine(). ' ###Teacher No.'.$i);
            return redirect()->back();
        }

        activity('survey_teacher')
            ->causedBy($user)
            ->withProperties(['memory' => round(memory_get_usage() / 1000000, 2).' MB'])
            ->log($user->name . ' đã đánh giá giảng viên');
        Session::flash('success', [
            'title' => 'Thành công',
            'content' => 'Đánh giá thành công, bạn có thể đóng cửa sổ',
        ]);

        return redirect()->back();
    }

}
