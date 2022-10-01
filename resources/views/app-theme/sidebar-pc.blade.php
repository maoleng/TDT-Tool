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
            <a {!! getAhrefTagContentPC(route('control_panel.build_schedule.index')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="side-menu__title">
                    Xếp lịch thời khóa biểu
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.build_schedule.index')) !!} style="pointer-events: none;cursor: default;">
                <div class="side-menu__icon">
                    <i data-lucide="refresh-cw"></i>
                </div>
                <div class="side-menu__title">
                    Tự động đọc thông báo
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.build_schedule.index')) !!} style="pointer-events: none;cursor: default;">
                <div class="side-menu__icon">
                    <i data-lucide="message-circle"></i>
                </div>
                <div class="side-menu__title">
                    Thông báo điểm
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
                    Cấu hình
                </div>
            </a>
        </li>
        @endif
    </ul>
</nav>
