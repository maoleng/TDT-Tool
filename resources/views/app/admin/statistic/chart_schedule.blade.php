<div id="modal-statistic-schedule" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body p-10 text-center">

                <a data-tw-dismiss="modal" href="javascript:;">
                    <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                </a>
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <div class="text-3xl mt-5">Thống kê nhập xuất thời khóa biểu</div>
                    </div>
                </div>


                <div class="intro-y col-span-12">
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_export_this_week" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Số lần xuất thời khóa biểu trong tuần này</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_export_this_month" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Số lần xuất thời khóa biểu trong tháng {{now()->month}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_export_this_year" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Số lần xuất thời khóa biểu trong năm {{now()->year}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_export_all" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Tổng sổ lần xuất thời khóa biểu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-[400px] mt-20">
                    <canvas id="chart_schedules_by_month"></canvas>
                </div>
                <div class="h-[400px] mt-20">
                    <canvas id="chart_users_export_schedule"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        Chart.register(ChartDataLabels);
        $('#statistic-schedule').on('click', function() {
            $.ajax({
                type: 'GET',
                url: '{{route('admin.statistic.schedule_exported')}}',
                data: {
                    _token: "{{csrf_token()}}",
                },
                success:function(data) {
                    // Dùng chung
                    data = Object.entries(data)
                    const background_color_schedule = [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                    ]
                    const border_color_schedule = [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                    ]

                    // Các div thống kê
                    $('#count_export_this_week').text(data[1][1])
                    $('#count_export_this_month').text(data[2][1])
                    $('#count_export_this_year').text(data[3][1])
                    $('#count_export_all').text(data[4][1])

                    // Biểu đồ thống kê
                    scheduleByMonths(data, background_color_schedule, border_color_schedule)
                    usersExportSchedule(data, background_color_schedule, border_color_schedule)
                }
            });
        })
    })

    function scheduleByMonths(data, background_color_schedule, border_color_schedule)
    {
        let schedule_by_months = Object.entries(data[0][1])
        let data_sets = []
        schedule_by_months.forEach(function (item, index, arr) {
            data_sets.push({
                label: 'Tổng số lần xuất thời khóa biểu theo tháng trong năm ' + item[0],
                data: item[1],
                backgroundColor: background_color_schedule,
                borderColor: border_color_schedule,
                borderWidth: 1,
            })
        })
        const data_chart_schedule_by_months = {
            labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map(function (month) {return 'Tháng ' + month}),
            datasets: data_sets
        };
        const config_data_chart_schedule_by_months = {
            type: 'bar',
            data: data_chart_schedule_by_months,
            options: getOptionSchedules(Math.max(...schedule_by_months[0][1])),
        };
        new Chart($('#chart_schedules_by_month'), config_data_chart_schedule_by_months);
    }

    function usersExportSchedule(data, background_color_schedule, border_color_schedule)
    {
        const data_user_export_schedule = {
            labels: data[5][1][0],
            datasets: [{
                label: 'Top ' + data[5][1][1].length + ' người dùng xuất nhiều thời khóa biểu nhất',
                data: data[5][1][1],
                backgroundColor: background_color_schedule,
                borderColor: border_color_schedule,
                borderWidth: 1
            }]
        };
        const config_user_export_schedule = {
            type: 'bar',
            data: data_user_export_schedule,
            options: getOptionSchedules(Math.max(...data[5][1][1])),
        };
        new Chart($('#chart_users_export_schedule'), config_user_export_schedule);
    }

    function getOptionSchedules(max_x_row)
    {
        return {
            maintainAspectRatio: false,
            plugins : {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: Math.round,
                    font: {
                        weight: 'bold',
                        size: 16
                    }
                },
                legend: {
                    display: true,
                    labels: {
                        color: "blue",
                        font: {
                            size: 24
                        },
                    }
                },
            },
            scales: {
                y: {
                    ticks: {
                        color: "yellow",
                        font: {
                            size: 18,
                        },
                        stepSize: 1,
                        beginAtZero: true,
                    },
                    suggestedMax: max_x_row + 5
                },
                x: {
                    ticks: {
                        color: "yellow",
                        font: {
                            size: 18
                        },
                        stepSize: 1,
                        beginAtZero: true,
                    },
                }
            }
        }
    }
</script>
