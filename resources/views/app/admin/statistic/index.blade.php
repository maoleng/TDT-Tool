@extends('app-theme.master')

@section('title')
    Thống kê chung vào hôm nay - {{now()->format('d/m/Y')}}
@endsection

@section('content')
    <div class="intro-y col-span-12">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="refresh-cw" data-lucide="refresh-cw" class="lucide lucide-refresh-cw report-box__icon text-primary"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"></path></svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{$count_seen_news}} thông báo</div>
                        <div class="text-base text-slate-500 mt-1">Số thông báo tự động đọc</div>
                    </div>
                </div>
            </div>
            {{--            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">--}}
            {{--                <div class="report-box zoom-in">--}}
            {{--                    <div class="box p-5">--}}
            {{--                        <div class="flex">--}}
            {{--                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card report-box__icon text-pending"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>--}}
            {{--                            <div class="ml-auto">--}}
            {{--                                <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">--}}
            {{--                                    <label class="form-check-label ml-0" for="show-example-1">Kích hoạt</label>--}}
            {{--                                    <input id="toggle-repeat" class="show-code form-check-input mr-0 ml-3" type="checkbox">--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="text-3xl font-medium leading-8 mt-6">3.721</div>--}}
            {{--                        <div class="text-base text-slate-500 mt-1">Số lần thông báo điểm</div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div id="statistic-mail" class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div data-tw-toggle="modal" data-tw-target="#modal-statistic-mail" class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="mail" data-lucide="mail" class="lucide lucide-mail report-box__icon text-warning"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{$count_sent_news}} mail</div>
                        <div class="text-base text-slate-500 mt-1">Số thông báo gửi qua mail</div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar" data-lucide="calendar" class="lucide lucide-calendar report-box__icon text-success"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{$count_created_schedules}} lần</div>
                        <div class="text-base text-slate-500 mt-1">Số lần xuất thời khóa biểu</div>
                    </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>

    @include('app.admin.statistic.chart_mail')
@endsection
{{--const data = {--}}
{{--labels: ["Html", "Vuejs", "Laravel"],--}}
{{--datasets: [{--}}
{{--data: [30, 30, 40],--}}
{{--label: 'My First dataset',--}}
{{--backgroundColor: [ 'rgb(255, 99, 132)',--}}
{{--'rgb(54, 162, 235)',--}}
{{--'rgb(255, 205, 86)'],--}}
{{--borderColor: 'rgb(255, 99, 132)',--}}
{{--hoverOffset: 4,--}}
{{--hoverBorderWidth: 7,--}}
{{--hoverBorderJoinStyle: 'round',--}}
{{--rotation: 30,--}}
{{--}]--}}
{{--}--}}
{{--const config = {--}}
{{--type: 'pie',--}}
{{--data: data,--}}
{{--options: {--}}
{{--maintainAspectRatio: false,--}}
{{--plugins: {--}}
{{--legend: {--}}
{{--labels: {--}}
{{--color: 'rgb(255, 99, 132)'--}}
{{--}--}}
{{--}--}}
{{--},--}}
{{--animation : {--}}
{{--animateScale: true,--}}
{{--}--}}
{{--}--}}
{{--};--}}
