<nav class="side-nav">
    <a href="{{route('index')}}" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Mao Leng" class="w-6" src="{{asset('app-theme/images/logo.png')}}" style="width:40px">
        <span class="hidden xl:block text-white text-lg ml-3">
            MaoLeng
        </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a {!! getAhrefTagContentPC(route('index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">
                    Trang chủ
                </div>
            </a>
        </li>
        <li class="side-nav__devider my-6"></li>
        @if (authed()->role !== 1)
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.read_notification.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">
                    Đọc thông báo mới
                </div>
            </a>
        </li>
        @endif
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.mail_notification.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="mail"></i>
                </div>
                <div class="side-menu__title">
                    Thông báo mới qua mail
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" {!! getAhrefTagContentPC(route('control_panel.build_schedule.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="side-menu__title">
                    Xếp lịch thời khóa biểu
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.teacher_survey.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="edit"></i>
                </div>
                <div class="side-menu__title">
                    Đánh giá giảng viên
                </div>
            </a>
        </li>
        @if (authed()->role === 3)
        <li class="side-nav__devider my-6"></li>
        <li>
            <a {!! getAhrefTagContentPC(route('admin.user.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="side-menu__title">
                    Quản lý người dùng
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('admin.config.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="clock"></i>
                </div>
                <div class="side-menu__title">
                    Cấu hình thời gian
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('admin.statistic.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="bar-chart-2"></i>
                </div>
                <div class="side-menu__title">
                    Thống kê
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('admin.activity_log.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="activity"></i>
                </div>
                <div class="side-menu__title">
                    Lịch sử hành động
                </div>
            </a>
        </li>
        @endif
    </ul>
</nav>
