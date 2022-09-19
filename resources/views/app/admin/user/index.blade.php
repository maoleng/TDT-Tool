@extends('app-theme.master')

@section('title')
    Các người dùng của hệ thống
@endsection

@section('content')
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2 table-hover">
            <thead>
            <tr>
                <th class="text-center whitespace-nowrap">Ảnh</th>
                <th class="text-center whitespace-nowrap">Tên & MSSV</th>
                <th class="text-center whitespace-nowrap">Vai trò</th>
                <th class="text-center whitespace-nowrap">Số mã chưa dùng</th>
                <th class="text-center whitespace-nowrap">Lưu mật khẩu</th>
                <th class="text-center whitespace-nowrap">Tình trạng</th>
                <th class="text-center whitespace-nowrap">Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="intro-x">
                    <td class="w-2">
                        <div class="flex justify-center items-center">
                            <div class="w-10 h-10 image-fit zoom-in">
                                <img class="tooltip rounded-full" src="{{$user->avatar}}">
                            </div>
                        </div>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">{{$user->name}}</span>
                        <div class="flex justify-center items-center text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            {{$user->studentId}}
                        </div>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">
                            {{$user->roleName}}
                        </span>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">
                            {{$user->promotions_count}}
                        </span>
                    </td>
                    <td class="table-report__action w-2">
                        <div class="flex justify-center items-center">
                            <span class="flex items-center">
                                @if($user->tdt_password)
                                    <i data-lucide="user-check"></i>
                                @else
                                    <i data-lucide="user-x"></i>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="table-report__action w-2">
                        <div class="flex items-center justify-center @if(!$user->active) text-danger @else text-success @endif">
                            <form action="{{route('admin.user.update', ['user' => $user])}}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="flex items-center">
                                    @if(!$user->active)
                                        <i data-lucide="lock"></i>
                                    @else
                                        <i data-lucide="unlock"></i>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="table-report__action w-2">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-4" href="javascript:;">
                                <i data-lucide="bar-chart" class="mr-1"></i>
                                Thống kê
                            </a>
                            <a class="flex items-center mr-4" data-tw-toggle="modal" data-tw-target="#promotion-of-{{$user->studentId}}" href="#">
                                <i data-lucide="code" class="mr-1"></i>
                                Mã
                            </a>
                            <a class="flex items-center mr-4" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                <i data-lucide="eye" class="mr-1"></i>
                                Quyền
                            </a>
                            <a class="flex items-center mr-4" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                <i data-lucide="file-text" class="mr-1"></i>
                                Lịch sử
                            </a>
                        </div>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>

        @foreach($users as $user)
        <div id="promotion-of-{{$user->studentId}}" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">Các mã {{$user->name}} đang sở hữu</h2>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-6">
                            <label for="crud-form-1" class="form-label">Thêm mã</label>
                            <input type="number" class="form-control col-span-2" placeholder="Nhập vào số mã" aria-label="default input inline 1">
                            <button class="btn btn-primary mt-5">Thêm</button>
                        </div>
                        <div class="col-span-12 sm:col-span-12 border-t">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="whitespace-nowrap">Mã</th>
                                    <th class="whitespace-nowrap">Tình trạng</th>
                                    <th class="whitespace-nowrap">Sử dụng/Khóa Vào</th>
                                    <th class="whitespace-nowrap">Tạo lúc</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->promotions as $promotion)
                                    <tr>
                                        <td>{{$promotion->code}}</td>
                                        <td>
                                            @if($promotion->status === null || $promotion->status === false)
                                                <div class="flex items-center @if($promotion->status === false) text-danger @else text-success @endif">
                                                    <form action="{{route('admin.promotion.update', ['promotion' => $promotion])}}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="flex items-center mr-3">
                                                            @if($promotion->status === false)
                                                                <i data-lucide="lock"></i>
                                                            @else
                                                                <i data-lucide="unlock"></i>
                                                            @endif
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                Đã dùng
                                            @endif
                                        </td>
                                        <td>{{$promotion->updated_at}}</td>
                                        <td>{{$promotion->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach


    </div>
@endsection


@section('script')

@endsection
