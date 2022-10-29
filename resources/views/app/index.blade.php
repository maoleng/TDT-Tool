@extends('app-theme.master')

@section('title')
    Trang chủ
@endsection

@section('content')
    <span class="intro-y col-span-12 overflow-auto lg:overflow-visible">
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
                            <a href="{{$notification->link}}" class="flex font-medium whitespace-nowrap text-lg tooltip" title="{{$notification->link_content}}">
                                {{$notification->notification_id}}
                            </a>
                        </div>
                    </td>
                    <td class="table-report__action w-2 h-5">
                        <span class="flex font-medium whitespace-nowrap text-lg tooltip" title="{{$notification->title}}">
                            {{$notification->short_title}}
                        </span>
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

    {{ $notifications->links('vendor.pagination.main') }}

@endsection

@section('script')

@endsection
