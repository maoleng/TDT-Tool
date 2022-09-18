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
                <a {!! getAhrefTagContentMB(route('control_panel.build_schedule.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="calendar"></i>
                    </div>
                    <div class="menu__title">
                        Xếp lịch thời khóa biểu
                    </div>
                </a>
            </li>
            <li>
                <a {!! getAhrefTagContentMB(route('control_panel.build_schedule.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="clock"></i>
                    </div>
                    <div class="menu__title">
                        Tự động đọc thông báo
                    </div>
                </a>
            </li>
            <li>
                <a {!! getAhrefTagContentMB(route('control_panel.build_schedule.index')) !!}>
                    <div class="menu__icon">
                        <i data-lucide="message-circle"></i>
                    </div>
                    <div class="menu__title">
                        Thông báo điểm
                    </div>
                </a>
            </li>



        </ul>
    </div>
</div>