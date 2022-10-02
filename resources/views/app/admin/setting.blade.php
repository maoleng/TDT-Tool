@extends('app-theme.master')

@section('title')
    Cài đặt của {{authed()->name}}
@endsection

@section('content')
    <div class="intro-y col-span-12">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="refresh-cw" data-lucide="refresh-cw" class="lucide lucide-refresh-cw report-box__icon text-primary"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"></path></svg>
                            <div class="ml-auto">
                                <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                    <label class="form-check-label ml-0" for="show-example-1">Kích hoạt</label>
                                    <form id="form-auto_read_notification" action="{{route('admin.setting.auto_read_notification')}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input name="auto_read_notification" id="toggle-auto_read_notification" @if ((bool)$settings->where('key', 'auto_read_notification')->first()->value === true) checked @endif class="show-code form-check-input mr-0 ml-3" type="checkbox">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">4.710</div>
                        <div class="text-base text-slate-500 mt-1">Số thông báo tự động đọc</div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card report-box__icon text-pending"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            <div class="ml-auto">
                                <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                    <label class="form-check-label ml-0" for="show-example-1">Kích hoạt</label>
                                    <input id="toggle-repeat" class="show-code form-check-input mr-0 ml-3" type="checkbox">
                                </div>
                            </div>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">3.721</div>
                        <div class="text-base text-slate-500 mt-1">Số lần thông báo điểm</div>
                    </div>
                </div>
            </div>
{{--            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">--}}
{{--                <div class="report-box zoom-in">--}}
{{--                    <div class="box p-5">--}}
{{--                        <div class="flex">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="monitor" data-lucide="monitor" class="lucide lucide-monitor report-box__icon text-warning"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>--}}
{{--                            <div class="ml-auto">--}}
{{--                                <div class="report-box__indicator bg-success tooltip cursor-pointer">--}}
{{--                                    12% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" data-lucide="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="text-3xl font-medium leading-8 mt-6">2.149</div>--}}
{{--                        <div class="text-base text-slate-500 mt-1">Total Products</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">--}}
{{--                <div class="report-box zoom-in">--}}
{{--                    <div class="box p-5">--}}
{{--                        <div class="flex">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="user" data-lucide="user" class="lucide lucide-user report-box__icon text-success"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>--}}
{{--                            <div class="ml-auto">--}}
{{--                                <div class="report-box__indicator bg-success tooltip cursor-pointer">--}}
{{--                                    22% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" data-lucide="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="text-3xl font-medium leading-8 mt-6">152.040</div>--}}
{{--                        <div class="text-base text-slate-500 mt-1">Unique Visitor</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
            $("#toggle-auto_read_notification").on('change', function() {
                $("#form-auto_read_notification").submit()
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
