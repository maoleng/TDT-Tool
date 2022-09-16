@extends('app-theme.master')

@section('title')
    Thêm mẫu tin nhắn
@endsection

@section('content')
    <form action="" id="form" method="post" class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            @csrf
            <div class="intro-y box p-5">
                <h2 class="font-medium text-base mr-auto">Nội dung mẫu tin nhắn</h2>
                <br>
                <div>
                    <label for="crud-form-1" class="form-label">Tiêu đề</label>
                    <input id="crud-form-1" type="text" name="title" class="form-control form-control-rounded w-full" placeholder="Tiêu đề của mail">
                </div>
                <div class="mt-3">
                    <label for="crud-form-1" class="form-label">Tên người gửi</label>
                    <input id="crud-form-1" type="text" name="sender" class="form-control form-control-rounded w-full" placeholder="Tên của người sẽ gửi mail cho bạn">
                </div>
                <div class="mt-3">
                    <label>Nội dung của mail</label>
                    <div class="mt-2">
                        <textarea name="content" id="myeditorinstance">Hello, World!</textarea>
                    </div>
                </div>
                <input type="hidden" name="date" id="date">
                <input type="hidden" name="time" id="time">
                <input type="hidden" name="cron_time" id="repeat_time">
            </div>
        </div>
    </form>

    <div class="intro-y col-span-12 lg:col-span-6">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Thời gian</h2>
                <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                    <label class="form-check-label ml-0" for="show-example-1">Lặp lại</label>
                    <input id="toggle-repeat" class="show-code form-check-input mr-0 ml-3" type="checkbox">
                </div>
            </div>
            <div id="basic-select" class="p-5">
                <div class="preview">
                    <div id="repeat" class="mt-5">
                        <div class="mt-5">
                            <label>Sẽ tự động gửi vào</label>
                            <div class="mt-2">
                                <input id="no_repeat_date" type="text" class="datepicker w-56 mx-auto" data-single-mode="true">
                                <select id="no_repeat_time" data-placeholder="Chọn giờ" class="tom-select w-full mt-5">
                                    @for($i = 0; $i <= 23; $i++)
                                        @if ($i < 10)<option value="0{{$i}}:00">{{$i}} giờ</option>
                                        @else<option value="{{$i}}:00">{{$i}} giờ</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
{{--                    ===============--}}
                    <div id="no-repeat" class="mt-5" style="display: none">
                        <div class="mt-5">
                            <label>Lặp lại sau mỗi</label>
                            <div class="mt-2">
                                <select id="repeat_queue" data-placeholder="Select your favorite actors" class="tom-select w-full">
                                    <option value="0 */2 * * *">2 giờ</option>
                                    <option value="0 */3 * * *">3 giờ</option>
                                    <option value="0 */4 * * *">4 giờ</option>
                                    <option value="0 */6 * * *">6 giờ</option>
                                    <option value="0 */8 * * *">8 giờ</option>
                                    <option value="0 */12 * * *">12 giờ</option>
                                    <option value="0 0 */1 * *">1 ngày</option>
                                    <option value="0 0 */2 * *">2 ngày</option>
                                    <option value="0 0 */3 * *">3 ngày</option>
                                    <option value="0 0 */4 * *">4 ngày</option>
                                    <option value="0 0 */5 * *">5 ngày</option>
                                    <option value="0 0 */6 * *">6 ngày</option>
                                    <option value="0 0 */7 * *">7 ngày</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-5">
                    <a href="" type="button" class="btn btn-outline-secondary w-24 mr-1">Hủy</a>
                    <button id="button_submit" type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
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

    <script src="https://cdn.tiny.cloud/1/free/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance',
            @if (isDarkMode())
            content_css: 'tinymce-5-dark',
            skin: 'oxide-dark',
            @endif
            height: 270,
            plugins: 'advcode table checklist image advlist autolink lists link charmap preview codesample imagetool fullscreen',
            toolbar: 'insertfile | blocks| bold italic | fullscreen | image | link | preview | codesample | bullist numlist checklist |  alignleft aligncenter alignright',
            menubar: 'insert view',
            mobile: {
                menubar: true
            },
            setup: function(editor) {
                editor.on('init', function (e) {
                    setTimeout(function() {
                        $("button[tabindex='-1'].tox-notification__dismiss.tox-button.tox-button--naked.tox-button--icon")[0].click()
                    }, 10);

                })
            },
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input')
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*')
                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader()
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime()
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache
                        var base64 = reader.result.split(',')[1]
                        var blobInfo = blobCache.create(id, file, base64)
                        blobCache.add(blobInfo)
                        cb(blobInfo.blobUri(), { title: file.name })
                    }
                    reader.readAsDataURL(file)
                }
                input.click()
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#toggle-repeat").on('change', function() {
                if($("#toggle-repeat").is(':checked')) {
                    $("#repeat").css('display', 'none')
                    $("#no-repeat").css('display', 'block')
                } else {
                    $("#repeat").css('display', 'block')
                    $("#no-repeat").css('display', 'none')
                }
            })

            $("#button_submit").on('click', function () {
                if ($("#toggle-repeat").is(':checked')) {
                    let repeat_time = $("#repeat_queue").val()
                    $("#date").val(null)
                    $("#time").val(null)
                    $("#repeat_time").val(repeat_time)
                } else {
                    let date = $("#no_repeat_date").val()
                    let time = $("#no_repeat_time").val()
                    $("#date").val(date)
                    $("#time").val(time)
                    $("#repeat_time").val(null)
                }
                $("#form").submit()
            })
        })
    </script>
@endsection
