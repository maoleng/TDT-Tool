@extends('app-theme.master')

@section('title')
    Nhận thông báo qua mail
@endsection

@section('content')
    <div class="intro-y col-span-12">
        <div class="intro-y box p-5">
            <div class="flex flex-col sm:flex-row items-center border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Chọn các khoa/phòng để theo dõi thông báo</h2>
                <form action="{{route('control_panel.mail_notification.choose_department')}}" method="post" class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                    @csrf
                    <label>Chọn nhanh: </label>
                    <input id="choose_fast" type="hidden" name="choose_fast">
                    <button id="choose_fast-all" class="choose_fast btn btn-primary w-32 mr-2 mb-2 ml-3">
                        <i data-lucide="layers" class="w-4 h-4 mr-2"></i>
                        Tất cả
                    </button>
                    <button id="choose_fast-default" class="choose_fast btn btn-warning w-32 mr-2 mb-2">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i>
                        Mặc định
                    </button>
                    <button id="choose_fast-delete" class="choose_fast btn btn-danger w-32 mr-2 mb-2">
                        <i data-lucide="trash" class="w-4 h-4 mr-2"></i>
                        Xóa tất cả
                    </button>
                </form>
            </div>
            <br>
            <form action="{{route('control_panel.mail_notification.choose_department')}}" method="post">
                @csrf
                <div class="mt-3">
                    <label>Khoa</label>
                    <select name="faculty[]" id="test" data-placeholder="Select your favorite actors" class="tom-select w-full mt-2" multiple>
                        @foreach($departments['faculty'] as $faculty)
                            <option @if(in_array($faculty->id, $subscribed_departments, true)) selected @endif value="{{$faculty->id}}">{{$faculty->departmentName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <label>Phòng/ban phổ biến</label>
                    <select name="popular[]" id="test" data-placeholder="Select your favorite actors" class="tom-select w-full mt-2" multiple>
                        @foreach($departments['popular'] as $department)
                            <option @if(in_array($department->id, $subscribed_departments, true)) selected @endif value="{{$department->id}}">{{$department->departmentName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <label>Phòng/ban khác</label>
                    <select name="other[]" id="test" data-placeholder="Select your favorite actors" class="tom-select w-full mt-2" multiple>
                        @foreach($departments['other'] as $department)
                            <option @if(in_array($department->id, $subscribed_departments, true)) selected @endif value="{{$department->id}}">{{$department->departmentName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection


@section('script')
    <script>
        $(document).ready(function () {
            $("button.choose_fast").on('click', function() {
                let type = $(this).attr('id').split('-')[1]
                $("#choose_fast").val(type)
            })
        })
    </script>
@endsection
