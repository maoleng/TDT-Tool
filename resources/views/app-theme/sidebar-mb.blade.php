<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="{{route('index')}}" class="flex mr-auto">
            <img alt="Mao Leng" class="w-6" src="{{asset('app-theme/images/logo.png')}}" style="width:40px">
        </a>
        <a href="javascript:;" class="mobile-menu-toggler">
            <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
        </a>
    </div>
    <div class="scrollable">
        <a href="{{route('index')}}" class="mobile-menu-toggler">
            <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i>
        </a>
        <ul class="scrollable__content py-2">
            <li>
                <a {!! getAhrefTagContentMB(route('index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="home"></i>
                    </div>
                    <div class="menu__title">
                        Trang chủ
                    </div>
                </a>
            </li>
            <li class="menu__devider my-6"></li>
            @if (authed()->role !== 1)
            <li>
                <a {!! getAhrefTagContentMB(route('control_panel.read_notification.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="file-text"></i>
                    </div>
                    <div class="menu__title">
                        Đọc thông báo mới
                    </div>
                </a>
            </li>
            @endif
            <li>
                <a {!! getAhrefTagContentMB(route('control_panel.mail_notification.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="mail"></i>
                    </div>
                    <div class="menu__title">
                        Thông báo mới qua mail
                    </div>
                </a>
            </li>
            <li>
                <a target="_blank" {!! getAhrefTagContentMB(route('control_panel.build_schedule.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="calendar"></i>
                    </div>
                    <div class="menu__title">
                        Xếp lịch thời khóa biểu
                    </div>
                </a>
            </li>
            <li>
                <a {!! getAhrefTagContentMB(route('control_panel.teacher_survey.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="edit"></i>
                    </div>
                    <div class="menu__title">
                        Đánh giá giảng viên
                    </div>
                </a>
            </li>
            @if (authed()->role === 3)
            <li class="nav__devider my-6"></li>
            <li>
                <a {!! getAhrefTagContentMB(route('admin.user.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="users"></i>
                    </div>
                    <div class="menu__title">
                        Quản lý người dùng
                    </div>
                </a>
            </li>
            <li>
                <a {!! getAhrefTagContentMB(route('admin.config.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="clock"></i>
                    </div>
                    <div class="menu__title">
                        Cấu hình thời gian
                    </div>
                </a>
            </li>
            <li>
                <a {!! getAhrefTagContentMB(route('admin.statistic.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="bar-chart-2"></i>
                    </div>
                    <div class="menu__title">
                        Thống kê
                    </div>
                </a>
            </li>
            <li>
                <a {!! getAhrefTagContentMB(route('admin.activity_log.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="activity"></i>
                    </div>
                    <div class="menu__title">
                        Lịch sử hành động
                    </div>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
