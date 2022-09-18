@extends('app-theme.master')

@section('title')
    Đọc thông báo
@endsection

@section('content')
    <form action="" id="form" method="post" class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            @csrf
            <div class="intro-y box p-5">
                <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400" style="padding-bottom:1.25rem">
                    <h2 class="font-medium text-base mr-auto">Đọc hết thông báo mới</h2>
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
                    <select name="promotion" id="no_repeat_time" data-placeholder="Chọn mã hiện có" class="tom-select w-full mt-5">
                        @for($i = 0; $i <= 5; $i++)
                            <option value="{{\Str::random(5)}}">{{Illuminate\Support\Str::random(5)}}</option>
                        @endfor
                    </select>
                </div>
                <div class="text-right mt-5">
                    <button id="button_submit" type="submit" class="btn btn-primary w-24">Tiến hành</button>
                </div>
                <input type="hidden" name="date" id="date">
                <input type="hidden" name="time" id="time">
                <input type="hidden" name="cron_time" id="repeat_time">
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
