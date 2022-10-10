<script>

</script>
<div id="modal-statistic-mail" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body p-10 text-center">

                <a data-tw-dismiss="modal" href="javascript:;">
                    <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                </a>
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <div class="text-3xl mt-5">Thống kê gửi mail</div>
                    </div>
                </div>


                <div class="intro-y col-span-12">
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_mail_this_week" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Số mail đã gửi trong tuần này</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_mail_this_month" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Số mail đã gửi trong tháng 10</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_mail_this_year" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Số mail đã gửi trong năm 2022</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div id="count_all" class="text-3xl font-medium leading-8 mt-6"></div>
                                    <div class="text-base text-slate-500 mt-1">Tổng số mail đã gửi từ trước tới giờ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-[400px] mt-20">
                    <canvas id="chart_mails_by_month"></canvas>
                </div>
                <div class="h-[400px] mt-20">
                    <canvas id="chart_mails_by_day"></canvas>
                </div>
                <div class="h-[400px] mt-20">
                    <canvas id="chart_users_receive_mail"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        Chart.register(ChartDataLabels);
        $('#statistic-mail').on('click', function() {
            $.ajax({
                type: 'GET',
                url: '{{route('admin.statistic.mail_sent')}}',
                data: {
                    _token: "{{csrf_token()}}",
                },
                success:function(data) {
                    // Dùng chung
                    data = Object.entries(data)
                    const background_color = [
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
                    const border_color = [
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

console.log(data)
                    // Các div thống kê
                    $('#count_mail_this_week').text(data[2][1])
                    $('#count_mail_this_month').text(data[3][1])
                    $('#count_mail_this_year').text(data[4][1])
                    $('#count_all').text(data[5][1])

                    // Biểu đồ thống kê
                    mailByMonths(data, background_color, border_color)
                    mailByDays(data, background_color, border_color)
                    usersReceiveMail(data, background_color, border_color)
                }
            });
        })
    })

    function mailByMonths(data, background_color, border_color)
    {
        let mail_by_months = Object.entries(data[0][1])
        let data_sets = []
        mail_by_months.forEach(function (item, index, arr) {
            data_sets.push({
                label: 'Tổng số mail đã gửi theo tháng trong năm ' + item[0],
                data: item[1],
                backgroundColor: background_color,
                borderColor: border_color,
                borderWidth: 1,
            })
        })
        const data_chart_mails_by_month = {
            labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map(function (month) {return 'Tháng ' + month}),
            datasets: data_sets
        };
        const config_chart_mails_by_month = {
            type: 'bar',
            data: data_chart_mails_by_month,
            options: getOptions(Math.max(...mail_by_months[0][1])),
        };
        new Chart($('#chart_mails_by_month'), config_chart_mails_by_month);
    }

    function mailByDays(data, background_color, border_color)
    {
        const data_chart_mails_by_day = {
            labels: [2, 3, 4, 5, 6, 7, 8].map(function (day) {return 'Thứ ' + day}),
            datasets: [{
                label: 'Tổng số tin nhắn đã gửi theo ngày trong tuần',
                data: data[1][1],
                backgroundColor: background_color,
                borderColor: border_color,
                borderWidth: 1
            }]
        };
        const config_chart_mails_by_day = {
            type: 'bar',
            data: data_chart_mails_by_day,
            options: getOptions(Math.max(...data[1][1])),
        };
        new Chart($('#chart_mails_by_day'), config_chart_mails_by_day);
    }

    function usersReceiveMail(data, background_color, border_color)
    {
        const data_chart_users_receive_mail = {
            labels: data[6][1][0],
            datasets: [{
                label: 'Top 10 người dùng nhận nhiều mail nhất',
                data: data[6][1][1],
                backgroundColor: background_color,
                borderColor: border_color,
                borderWidth: 1
            }]
        };
        const config_chart_users_receive_mail = {
            type: 'bar',
            data: data_chart_users_receive_mail,
            options: getOptions(Math.max(...data[6][1][1])),
        };
        new Chart($('#chart_users_receive_mail'), config_chart_users_receive_mail);
    }

    function getOptions(max_x_row)
    {
        console.log(max_x_row)
        console.log(max_x_row + 10)
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
                    suggestedMax: max_x_row + 10
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
