@extends('app-theme.master')

@section('title')
    Xem lịch sử hành động của hệ thống
@endsection

@section('content')
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2 table-hover">
            <thead>
            <tr>
                <th class="text-center whitespace-nowrap">Loại</th>
                <th class="text-center whitespace-nowrap">Mô tả</th>
                <th class="text-center whitespace-nowrap">Dung lượng</th>
                <th class="text-center whitespace-nowrap">Ngày hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($activities as $activity)
                <tr class="intro-x">
                    <td class="w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">
                            {{$activity->log}}
                        </span>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">
                            {{$activity->description}}
                        </span>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">
                            {{$activity->getExtraProperty('memory')}}
                        </span>
                    </td>
                    <td class="table-report__action w-2">
                        <span class="flex justify-center items-center font-medium whitespace-nowrap">
                            {{$activity->created_at}}
                        </span>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>

        {{ $activities->links('vendor.pagination.main') }}
    </div>

@endsection


@section('script')

@endsection
