@extends('app-theme.master')

@section('title')
    Đọc thông báo
@endsection

@section('content')
    <form action="{{route('control_panel.read_notification.read_news')}}" id="form" method="post" class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            @csrf
            <div class="intro-y box p-5">
                <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400" style="padding-bottom:1.25rem">
                    <h2 class="font-medium text-base mr-auto">Đọc thông báo mới</h2>
                </div>
                <div class="mt-5">
                    <label for="crud-form-1" class="form-label">Mã số sinh viên</label>
                    <input id="crud-form-1" type="text" class="form-control form-control-rounded w-full" placeholder="{{authed()->student_id}}" disabled>
                </div>
                <div class="mt-5">
                    <label for="crud-form-1" class="form-label">Mật khẩu</label>
                    <input id="crud-form-1" type="password" name="tdt_password" class="form-control form-control-rounded w-full" placeholder="Nhập mật khẩu của tài khoản stdtportal">
                </div>
                <div class="mt-5">
                    <label>Mã kích hoạt</label>
                    <select name="code" id="no_repeat_time" data-placeholder="Chọn mã hiện có" class="tom-select w-full mt-3">
                        @foreach($promotions as $promotion)
                            <option value="{{$promotion->id}}">{{$promotion->code}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-right mt-5">
                    <button id="button_submit" type="submit" class="btn btn-primary w-24">Tiến hành</button>
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="mt-5"></div>
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
                    <div class="mt-5"></div>
                    <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
                        <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i>
                        {{session()->get('message')}}
                        <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </form>

    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Đọc toàn bộ thông báo</h2>
                <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                    <i data-lucide="lock" class="mr-0 ml-3"></i>
                    <label class="form-check-label ml-3" for="show-example-1">VIP USER</label>
                </div>
            </div>
            <div id="basic-select" class="p-5">
                <div>
                    <label for="crud-form-1" class="form-label">Mã số sinh viên</label>
                    <input id="crud-form-1" type="text" name="title" class="form-control form-control-rounded w-full" placeholder="{{authed()->student_id}}" disabled>
                </div>
                <div class="mt-5">
                    <label for="crud-form-1" class="form-label">Mật khẩu</label>
                    <input id="crud-form-1" type="password" name="sender" class="form-control form-control-rounded w-full" placeholder="Nhập mật khẩu của tài khoản stdtportal">
                </div>
                <div class="text-right mt-5">
                    <button id="button_submit" type="submit" class="btn btn-primary w-24">Tiến hành</button>
                </div>
            </div>
        </div>
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

@endsection
