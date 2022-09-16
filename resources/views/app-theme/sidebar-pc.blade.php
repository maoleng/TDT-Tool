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
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.read_notification')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">
                    Đọc thông báo mới
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.mail_notification')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="mail"></i>
                </div>
                <div class="side-menu__title">
                    Thông báo mới qua mail
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.build_schedule')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="side-menu__title">
                    Xếp lịch thời khóa biểu
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.build_schedule')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="clock"></i>
                </div>
                <div class="side-menu__title">
                    Tự động đọc thông báo
                </div>
            </a>
        </li>
        <li>
            <a {!! getAhrefTagContentPC(route('control_panel.build_schedule')) !!}>
                <div class="side-menu__icon">
                    <i data-lucide="message-circle"></i>
                </div>
                <div class="side-menu__title">
                    Thông báo điểm
                </div>
            </a>
        </li>
    </ul>
</nav>
