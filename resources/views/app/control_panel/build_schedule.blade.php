@extends('app-theme.master')

@section('title')
    Xếp lịch học vào Google Calendar
@endsection

@section('help_modal')
    <div class="p-5 text-center">
        <i data-lucide="check-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
        <div class="text-3xl mt-5">Hướng dẫn xếp lịch học vào các trình quản lý lịch</div>
    </div>
    <div class="px-5 pb-8">
        <div class="text-2xl mt-5">Hướng dẫn nhanh:</div>
        <div class="text-xl text-slate-500 mt-2">
            Vào trang thời khóa biểu tổng quát, nhấn Ctrl + U, copy và paste vào phần <b>Nhập lịch</b>
            <br>
            <strong>Để bảo mật hơn</strong> thì các bạn nên <b>xóa thẻ form</b> nằm ngay dưới thẻ body
        </div>
    </div>
    <div class="px-5 pb-8">
        <div class="text-2xl mt-5">Hướng dẫn chi tiết: (nhân đôi tab để làm theo cho dễ)</div>
        <div class="intro-y col-span-12">
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        1. Vào thời khóa biểu <img class="w-52 mt-2.5" src="{{asset('img/help/schedule.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-8 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        2. Chọn <b>học kì hiện tại</b> và ấn vào <b>Xem thời khóa biểu tổng quát</b> <img class="mt-2.5" src="{{asset('img/help/schedule1.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        3. Nhấn tổ hợp phím <b>Ctrl + U</b> hoặc nhấn chuột phải và chọn <b>View Page Source</b> <img class="mt-2.5" src="{{asset('img/help/schedule2.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        4. Copy toàn bộ nội dung này bằng tổ hợp phím <b>Ctrl + A</b> và <b>Ctrl + C</b> <img class="mt-2.5" src="{{asset('img/help/schedule3.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        5. Dán nội dung vừa copy vào đây <img class="mt-2.5" src="{{asset('img/help/schedule4.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        6. (Có thể bỏ qua bước này) Để bảo mật hơn bạn vui lòng xóa token bằng cách
                        <ul>
                            <li>- Copy dòng này: </li>
                            <li>
                                <b>{{'<form name="form1" method="post" action="tkb2.aspx?Token='}}</b></li>
                            <li>- Ấn tổ hợp phím <b>Ctrl + F</b> và <b>Ctrl + V</b></li>
                            <li>- Xóa dòng đó đi</li>
                        </ul>
                        <img class="mt-2.5" src="{{asset('img/help/schedule5.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        7. Ấn nút <b>Nhập lịch</b> và nút <b>Xuất lịch</b>
                        <img class="mt-2.5" src="{{asset('img/help/schedule6.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        8. Vào <a href="https://calendar.google.com/">calendar.google.com</a>, chọn <b>Cài đặt</b>
                        <img class="mt-2.5" src="{{asset('img/help/schedule7.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        9. Chọn <b>Nhập và xuất</b>
                        <img class="mt-2.5" src="{{asset('img/help/schedule8.png')}}" alt="">
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-12 intro-y">
                    <div class="text-xl text-slate-500 mt-2">
                        10. Nhập lịch vào trình quản lí lịch <b>theo như hình</b>
                        <img class="mt-2.5" src="{{asset('img/help/schedule9.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="p-5 text-center">
            <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{route('control_panel.build_schedule.store')}}" method="post" class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y col-span-12">
            @csrf
            <div class="intro-y box p-5">
                <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mb-5 font-medium text-base mr-auto">Nhập lịch</h2>
                </div>
                <div class="mt-5">
                    <div>
                        <label for="crud-form-1" class="form-label">Mã nguồn thời khóa biểu tổng quát</label>
                        <textarea type="text" name="source" class="form-control form-control-rounded w-full" placeholder="<!DOCTYPE HTML>...."></textarea>
                    </div>
                    <div class="text-right mt-5">
                        <button class="btn btn-primary w-32 mr-2 mb-2">
                            <i data-lucide="file-plus" class="w-4 h-4 mr-2"></i> Nhập lịch
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Xuất lịch</h2>
            </div>
            <form action="{{route('control_panel.build_schedule.download')}}" method="post" class="p-5">
                @csrf
                <div class="">
                    <label>Xuất lịch bắt đầu từ</label>
                    <div class="flex flex-col sm:flex-row mt-2">
                        <div class="form-check mr-5">
                            <input id="radio-switch-2" class="form-check-input" type="radio" checked name="start_at" value="now">
                            <label class="form-check-label" for="radio-switch-2">Hôm nay đến hết học kì</label>
                        </div>
                        <div class="form-check mr-2 mt-2 sm:mt-0">
                            <input id="radio-switch-1" class="form-check-input" type="radio" name="start_at" value="begin">
                            <label class="form-check-label" for="radio-switch-1">Đầu học kì đến hết học kì</label>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <label>Định dạng tệp</label>
                    <div class="flex flex-col sm:flex-row mt-2">
                        <div class="form-check mr-5">
                            <input checked id="radio-switch-2" class="form-check-input" type="radio" name="file_type" value="ics">
                            <label class="form-check-label" for="radio-switch-2">.ics</label>
                        </div>
                        <div class="form-check mr-2 mt-2 sm:mt-0">
                            <input id="radio-switch-1" class="form-check-input" type="radio" name="file_type" value="csv">
                            <label class="form-check-label" for="radio-switch-1">.csv</label>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="text-right mt-5">
                        <button class="btn btn-pending w-32 mr-2 mb-2" @if (checkCreatedSchedule() === false) disabled @endif>
                            <i data-lucide="download" class="w-4 h-4 mr-2"></i> Xuất lịch
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="intro-y col-span-12 lg:col-span-6">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
                    <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i>
                    {{ $error }}
                    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endforeach
        @endif
        @if(session()->has('message'))
            <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
                <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i>
                {{session()->get('message')}}
                <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
    </div>


    @if(session()->has('success'))
        <div id="success-modal-preview" class="modal overflow-y-auto show" tabindex="-1" aria-hidden="false" style="padding-left: 0px; margin-top: 0px; margin-left: 0px; z-index: 10000;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle w-16 h-16 text-success mx-auto mt-3"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <div class="text-3xl mt-5">{{session()->get('success')['title']}}</div>
                            <div class="text-slate-500 mt-2">{{session()->get('success')['content']}}</div>
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection


@section('script')
    <script>
        $(document).ready(function () {
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
