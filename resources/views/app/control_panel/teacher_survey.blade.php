@extends('app-theme.master')

@section('title')
    Đánh giá chất lượng giảng viên
@endsection

@section('content')
    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y col-span-12">
            @csrf
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">Tài khoản đánh giá</h2>
                    <div title="Không lưu mật khẩu hoặc dùng cho việc gì khác" class="tooltip form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                        <i data-lucide="lock" class="mr-0 ml-3"></i>
                        <label class="form-check-label ml-3" for="show-example-1">SECURITY</label>
                    </div>
                </div>
                <div id="basic-select" class="p-5">
                    <div>
                        <label class="form-label">Mã số sinh viên</label>
                        <input type="text" class="form-control form-control-rounded w-full" placeholder="{{authed()->student_id}}" disabled>
                    </div>
                    <div class="mt-5 mb-5">
                        <label class="form-label">Mật khẩu</label>
                        <input id="i-temp-password" type="password" class="form-control form-control-rounded w-full" placeholder="Nhập mật khẩu của tài khoản stdtportal">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div title="Tùy chỉnh này áp dụng cho mọi giáo viên" class="tooltip flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Tùy chỉnh đánh giá</h2>
            </div>
            <form id="form-survey" action="{{route('control_panel.teacher_survey.survey')}}" method="post" class="p-5">
                @csrf
                <input id="i-password" type="hidden" name="password">
                <div>
                    <label>Mức độ đánh giá</label>
                    <div class="form-check mt-2" title="">
                        <input checked id="radio-switch-1" class="form-check-input" type="radio" name="level" value="34">
                        <label class="form-check-label" for="radio-switch-1">Ngẫu nhiên <b><i>khá đồng ý</i></b> hoặc <b>đồng ý</b></label>
                    </div>
                    <div class="form-check mt-2">
                        <input id="radio-switch-2" class="form-check-input" type="radio" name="level" value="45">
                        <label class="form-check-label" for="radio-switch-2">Ngẫu nhiên <b><i>đồng ý</i></b> hoặc <b><i>rất đồng ý</i></b></label>
                    </div>
                    <div class="form-check mt-2">
                        <input id="radio-switch-3" class="form-check-input" type="radio" name="level" value="35">
                        <label class="form-check-label" for="radio-switch-3">Ngẫu nhiên <b><i>khá đồng ý</i></b> hoặc <b><i>đồng ý</i></b> hoặc <b><i>rất đồng ý</i></b></label>
                    </div>
                </div>
                <div class="mt-5">
                    <label for="crud-form-1" class="form-label">Anh-chị hài lòng điều gì nhất đối với GV của môn học này?</label>
                    <textarea type="text" name="satisfy_text" class="form-control form-control-rounded w-full">Thầy cô rất tận tâm và nhiệt tình</textarea>
                </div>
                <div class="mt-5">
                    <label for="crud-form-1" class="form-label">Anh-chị còn có ý kiến đóng góp khác nhằm giúp nâng cao hiệu quả giảng dạy của GV môn học này?</label>
                    <textarea type="text" name="idea_text" class="form-control form-control-rounded w-full">Em rất hài lòng</textarea>
                </div>
                <div class="text-right mt-5">
                    <button id="btn-submit" type="button" class="btn btn-primary w-24">Tiến hành</button>
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
            $("#btn-submit").on('click', function() {
                $("#i-password").val($('#i-temp-password').val())
                $('#form-survey').submit()
            })
        })
    </script>
@endsection
