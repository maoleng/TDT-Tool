@extends('app-theme.master')

@section('title')
    Trang chủ
@endsection

@section('content')
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2 table-hover">
            <thead>
            <tr>
                <th class="text-center whitespace-nowrap text-lg text-slate-500">Mã</th>
                <th class="whitespace-nowrap text-lg text-slate-500">Tiêu đề</th>
                <th class="whitespace-nowrap text-lg text-slate-500">Phòng ban</th>
                <th class="whitespace-nowrap text-lg text-slate-500">Ngày đăng</th>
            </tr>
            </thead>
            <tbody>
            @foreach($notifications as $notification)
                <tr class="intro-x">
                    <td class="w-2">
                        <div class="flex justify-center items-center">
                            <a href="{{$notification->link}}" target="_blank" class="flex font-medium whitespace-nowrap text-lg tooltip" title="{{$notification->link_content}}">
                                {{$notification->notification_id}}
                            </a>
                        </div>
                    </td>
                    <td class="table-report__action w-2">
                        <a data-tw-toggle="modal" data-tw-target="#notification-{{$notification->notification_id}}" href="#" class="flex font-medium whitespace-nowrap text-lg tooltip" title="{{$notification->title}}">
                            {{$notification->short_title}}
                        </a>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex font-medium whitespace-nowrap text-lg">
                            {{$notification->department->department_name}}
                        </span>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex font-medium whitespace-nowrap text-lg whitespace-nowrap mt-0.5 tooltip" title="{{$notification->created_diff}}">
                            {{$notification->created_at}}
                        </span>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
    {{ $notifications->links('vendor.pagination.main') }}

    @foreach ($notifications as $notification)
        <div id="notification-{{$notification->notification_id}}" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl"">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto text-center">{{$notification->title}}</h2>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        {{$notification->content}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('script')

@endsection
