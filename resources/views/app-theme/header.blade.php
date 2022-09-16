<div class="top-bar">

    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route($route)}}">{{$menu}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$breadcrumb}}</li>
        </ol>
    </nav>


    <div class="intro-x relative mr-3 sm:mr-6">
        <div class="search hidden sm:block">
            <input type="text" class="search__input form-control border-transparent" placeholder="Không tìm kiếm được đâu...">
            <i data-lucide="search" class="search__icon dark:text-slate-500"></i>
        </div>
        <a class="notification sm:hidden" href="">
            <i data-lucide="search" class="notification__icon dark:text-slate-500"></i>
        </a>
    </div>


    <div class="intro-x dropdown mr-auto sm:mr-6">
    </div>


    <div class="intro-x relative mr-3 sm:mr-6">
        <div class="form-check form-switch">
            <input @if(isDarkMode()) checked @endif id="toggle-theme" class="form-check-input" type="checkbox">
            <label class="form-check-label" for="checkbox-switch-7">Chế độ tối</label>
        </div>
    </div>


    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
            <img alt="MaoLeng - EmailDaily" src="{{authed()->avatar}}">
        </div>
        <div class="dropdown-menu w-56">
            <ul class="dropdown-content bg-primary text-white">
                <li class="p-2">
                    <div class="font-medium">{{authed()->name}}</div>
                </li>
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i> Trang cá nhân
                    </a>
                </li>
{{--                <li>--}}
{{--                    <a href="" class="dropdown-item hover:bg-white/5">--}}
{{--                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Add Account--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="" class="dropdown-item hover:bg-white/5">--}}
{{--                        <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Reset Password--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="" class="dropdown-item hover:bg-white/5">--}}
{{--                        <i data-lucide="help-circle" class="w-4 h-4 mr-2"></i> Help--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="{{route('logout')}}" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>
