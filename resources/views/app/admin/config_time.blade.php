@extends('app-theme.master')

@section('title')
    Cấu hình thời gian cho năm học
@endsection

@section('content')
    <div class="intro-y col-span-12 lg:col-span-6">

    <form action="{{route('admin.config.update_start_study_weeks')}}" method="post" class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            @method('PUT')
            @csrf
            <div class="intro-y box p-5">
                <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400" style="padding-bottom:1.25rem">
                    <h2 class="font-medium text-base mr-auto">Tuần học đầu tiên của năm học {{$semester->yearRange}}</h2>
                </div>
                <br>
                <div>
                    <label class="form-label">Học kì 1</label>
                    <input type="text" name="semester_1" value="{{ $start_study_weeks['semester_1'] }}" class="form-control form-control-rounded w-full" placeholder="5">
                </div>
                <div class="mt-5">
                    <label class="form-label">Học kì 2</label>
                    <input type="text" name="semester_2" value="{{ $start_study_weeks['semester_2'] }}" class="form-control form-control-rounded w-full" placeholder="5">
                </div>
                <div class="mt-5">
                    <label class="form-label">Học kì hè</label>
                    <input type="text" name="semester_3" value="{{ $start_study_weeks['semester_3'] }}" class="form-control form-control-rounded w-full" placeholder="5">
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </form>
        <form action="{{route('admin.config.create_study_plan')}}" method="post" class="intro-y col-span-12 lg:col-span-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-6">
                @csrf
                <div class="intro-y box p-5">
                    <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400" style="padding-bottom:1.25rem">
                        <h2 class="font-medium text-base mr-auto">Kế hoạch năm học {{$semester->yearRange}}</h2>
                    </div>
                    <br>
                    <div>
                        <label class="form-label">Ngày bắt đầu năm học</label>
                        <input type="text" name="start_date" value="{{$first_date}}" class="form-control form-control-rounded w-full" placeholder="5">
                    </div>
                    <div class="mt-5">
                        <label class="form-label">Ngày kết thúc năm học</label>
                        <input type="text" name="end_date" value="{{$last_date}}" class="form-control form-control-rounded w-full" placeholder="5">
                    </div>
                    <div class="mt-5">
                        <label class="form-label">Ngày bắt đầu học kì 1</label>
                        <input type="text" name="semester_1_start_date" value="{{$first_date_semester_1}}" class="form-control form-control-rounded w-full" placeholder="5">
                    </div>
                    <div class="mt-5">
                        <label class="form-label">Ngày bắt đầu học kì 2</label>
                        <input type="text" name="semester_2_start_date" value="{{$first_date_semester_2}}" class="form-control form-control-rounded w-full" placeholder="5">
                    </div>
                    <div class="mt-5">
                        <label class="form-label">Ngày bắt đầu học kì hè</label>
                        <input type="text" name="semester_3_start_date" value="{{$first_date_semester_3}}" class="form-control form-control-rounded w-full" placeholder="5">
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Tùy chỉnh thời gian các tiết học</h2>
            </div>
            <form action="{{route('admin.config.update_period')}}" method="post" class="p-5">
                <div class="preview">
                    @csrf
                    @method('PUT')
                    @foreach($periods as $period)
                        <div class="grid grid-cols-12 gap-2 pt-5 @if (!$loop->first) pt-5 @endif">
                            <span class="font-semibold mt-3 col-span-2">Tiết {{$period['period']}}:</span>
                            <input type="text" name="started_ed|{{$period['id']}}" value="{{$period['started_ed']}} {{$period['ended_at']}}" class="form-control col-span-4" aria-label="default input inline 1">
                        </div>
                    @endforeach
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </form>
        </div>
    </div>

{{--    <div class="intro-y col-span-12">--}}
{{--        <div class="intro-y box p-5">--}}
{{--            <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400">--}}
{{--                <h2 class="font-medium text-base mr-auto">Chọn các khoa/phòng để theo dõi thông báo</h2>--}}
{{--                <form action="{{route('control_panel.mail_notification.choose_department')}}" method="post" class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">--}}
{{--                    @csrf--}}
{{--                    <label>Chọn nhanh: </label>--}}
{{--                    <input id="choose_fast" type="hidden" name="choose_fast">--}}
{{--                    <button id="choose_fast-all" class="choose_fast btn btn-primary w-32 mr-2 mb-2 ml-3">--}}
{{--                        <i data-lucide="layers" class="w-4 h-4 mr-2"></i>--}}
{{--                        Tất cả--}}
{{--                    </button>--}}
{{--                    <button id="choose_fast-default" class="choose_fast btn btn-warning w-32 mr-2 mb-2">--}}
{{--                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i>--}}
{{--                        Mặc định--}}
{{--                    </button>--}}
{{--                    <button id="choose_fast-delete" class="choose_fast btn btn-danger w-32 mr-2 mb-2">--}}
{{--                        <i data-lucide="trash" class="w-4 h-4 mr-2"></i>--}}
{{--                        Xóa tất cả--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--            <br>--}}
{{--            <form action="{{route('control_panel.mail_notification.choose_department')}}" method="post">--}}
{{--                <div class="text-right mt-5">--}}
{{--                    <button type="submit" class="btn btn-primary w-24">Lưu</button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection


@section('script')


    <script>
        $(document).ready(function () {


            $("#toggle-repeat").on('change', function() {
                if($("#toggle-repeat").is(':checked')) {
                    $("#repeat").css('display', 'none')
                    $("#no-repeat").css('display', 'block')
                } else {
                    $("#repeat").css('display', 'block')
                    $("#no-repeat").css('display', 'none')
                }
            })

            @if (isset($template->cron_time))
            $("#toggle-repeat").click()
            @endif

            $("#button_submit").on('click', function () {
                if ($("#toggle-repeat").is(':checked')) {
                    let repeat_time = $("#repeat_queue").val()
                    $("#date").val(null)
                    $("#time").val(null)
                    $("#repeat_time").val(repeat_time)
                } else {
                    let date = $("#no_repeat_date").val()
                    let time = $("#no_repeat_time").val()
                    $("#date").val(date)
                    $("#time").val(time)
                    $("#repeat_time").val(null)
                }

                $("#form").submit()
            })
        })
    </script>
@endsection
