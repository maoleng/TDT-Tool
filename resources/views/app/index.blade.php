@extends('app-theme.master')

@section('header')
    <div class="intro-x dropdown mr-auto sm:mr-6">
        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#filter">
            <i data-lucide="filter" class="w-10 h-10 mr-2"></i>
        </a>
    </div>

    <div id="filter" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="form" action="{{route('index')}}" class="modal-body p-10 text-center">

                    <a data-tw-dismiss="modal" href="javascript:;">
                        <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                    <div class="modal-body p-0">
                        <div class="text-center">
                            <div class="text-3xl">Bộ lọc</div>
                        </div>
                    </div>

                    <div class="intro-y grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 lg:col-span-6">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Lọc theo phòng ban</h2>
                            </div>
                            <div class="mt-3">
                                <label>Khoa</label>
                                <select data-placeholder="Select your favorite actors" class="department tom-select w-full mt-2" multiple>
                                    @foreach($departments['faculty'] as $faculty)
                                        <option @if (in_array($faculty->id, $search_data['department_ids'] ?? [], true)) selected @endif value="{{$faculty->id}}">{{$faculty->departmentName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-5">
                                <label>Phòng/ban phổ biến</label>
                                <select data-placeholder="Select your favorite actors" class="department tom-select w-full mt-2" multiple>
                                    @foreach($departments['popular'] as $department)
                                        <option @if (in_array($department->id, $search_data['department_ids'] ?? [], true)) selected @endif value="{{$department->id}}">{{$department->departmentName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-5">
                                <label>Phòng/ban khác</label>
                                <select data-placeholder="Select your favorite actors" class="department tom-select w-full mt-2" multiple>
                                    @foreach($departments['other'] as $department)
                                        <option @if (in_array($department->id, $search_data['department_ids'] ?? [], true)) selected @endif value="{{$department->id}}">{{$department->departmentName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-span-12 lg:col-span-6">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Lọc theo ngày đăng</h2>
                            </div>
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 sm:col-span-6">
                                    <label for="modal-datepicker-1" class="form-label">Từ</label>
                                    <input value="{{$search_data['from_date'] ?? 'null'}}" name="from_date" id="from_date" type="text" class="datepicker form-control" data-single-mode="true">
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label for="modal-datepicker-2" class="form-label">Đến</label>
                                    <input value="{{$search_data['to_date'] ?? 'null'}}" name="to_date" id="to_date" type="text" class="datepicker form-control" data-single-mode="true">
                                </div>
                            </div>

                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Lọc thông báo theo ngày</h2>
                            </div>
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 sm:col-span-6">
                                    <input value="{{$search_data['in_date'] ?? 'null'}}" name="in_date" id="in_date" type="text" class="datepicker form-control" data-single-mode="true">
                                </div>
                            </div>
                        </div>
                    </div>

                    <input id="department" type="hidden" name="department_ids">

                    <div class="modal-footer text-right">
                        <button id="button_reset" type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Đặt lại</button>
                        <button id="button_filter" type="button" class="btn btn-primary w-20">Lọc</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="intro-x relative mr-3 sm:mr-6">
        <form action="">
            <div class="search hidden sm:block">
                <input type="search" name="q" value="{{$search_data['q'] ?? ''}}" class="search__input form-control border-transparent" placeholder="Tìm kiếm theo tiêu đề hoặc nội dung...">
                <i data-lucide="search" class="search__icon dark:text-slate-500"></i>
            </div>
            <a class="notification sm:hidden" href="">
                <i data-lucide="search" class="notification__icon dark:text-slate-500"></i>
            </a>
        </form>
    </div>
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
    {{ $notifications->appends(request()->input())->links('vendor.pagination.main') }}

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
    <script>
        $(document).ready(function() {
            $('#button_filter').on('click', function() {
                let department_ids = $('.department :selected').map(function() {return $(this).val()}).get();
                $('#department').val(department_ids)

                $('#form').submit()
            })

            $('#button_reset').on('click', function() {
                $('#department').val(null)
                $('#from_date').val(null)
                $('#to_date').val(null)
                $('#in_date').val(null)
                $('#form').submit()
            })
        })
    </script>
@endsection
