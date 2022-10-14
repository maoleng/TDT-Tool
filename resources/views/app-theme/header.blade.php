<div class="top-bar">

    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route($route)}}">{{$menu}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$breadcrumb}}</li>
        </ol>
    </nav>


    <div class="intro-x dropdown mr-auto sm:mr-6">
        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#help">
            <i data-lucide="help-circle" class="w-10 h-10 mr-2"></i>
        </a>
    </div>

    <div id="help" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0">
                    @yield('help_modal')
                </div>
            </div>
        </div>
    </div>

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
                <li>
                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#success-modal-preview" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Cài đặt
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="{{route('logout')}}" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div id="success-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="code" class="w-16 h-16 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Cài đặt</div>
{{--                        <div class="text-slate-500 mt-2">You clicked the button!</div>--}}
                    </div>
                    @if (authed()->id === \App\Models\User::MASTER_ID)
                        <div class="p-5 text-center">
                            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                <label class="form-check-label ml-0" for="show-example-1">Tự động đọc thông báo</label>
                                <form id="form-auto_read_notification" action="{{route('admin.setting.auto_read_notification')}}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input name="auto_read_notification" id="toggle-auto_read_notification" @if ((bool)getSettings()->where('key', 'auto_read_notification')->first()->value === true) checked @endif class="show-code form-check-input mr-0 ml-3" type="checkbox">
                                </form>
                            </div>
                        </div>
                        <div class="p-5 pb-8 text-center">
                            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                <label class="form-check-label ml-0" for="show-example-1">Thông báo khi có điểm</label>
                                <form id="form-auto_read_notification" action="{{route('admin.setting.auto_read_notification')}}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input name="auto_read_notification" id="toggle-auto_read_notification"  class="show-code form-check-input mr-0 ml-3" type="checkbox">
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
