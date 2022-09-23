{!! $content !!}
<br>
<hr>
<h3>File đính kèm:
    @foreach($files as $name => $file_link)
        <a href="{{$file_link}}">{{$name}}</a>
    @endforeach
</h3>
<hr>
<h3>Đăng bởi: {{$department}}</h3>
<h3>Đăng lúc: {{$created_at}}</h3>
<h3>Link bài viết: <a href="{{$link}}">{{$link}}</a></h3>
<h3>Bạn nhận được mail này là vì bạn đang đã đăng kí nhận thông báo từ {{\URL::to('/')}}</h3>
<hr>
<br>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size: 14px; font-family: Open Sans, sans-serif; margin: 0px; padding: 0px; border: 0px; outline: 0px;">
    <tr>
        <td colspan="3" style="text-align: center;">
            <img src="{{getConfigValue('mail_notification.header_logo')}}" alt="" style="width: 200px; margin-bottom: 25px;border-radius: 100%;border: 5px solid white;outline: 5px solid grey">
        </td>
    </tr>
    <tr style="text-align: center;">
        <td>
            <div style="display: block; float: right; width: 70px; height: 2px; background: #000;"></div>
        </td>
        <td style="text-transform: uppercase; font-size: 80px; width: 150px; font-weight: 300; letter-spacing: 4px; padding: 0; margin: 0;">MaoLeng</td>
        <td>
            <div style="display: block; float: left; width: 70px; height: 2px; background: #000;"></div>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; text-transform: uppercase; font-size: 40px; font-weight: bold; letter-spacing: 10px; padding: 0; margin: 0;">Bùi Hữu Lộc</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; text-transform: uppercase; font-size: 30px; font-weight: 300; letter-spacing: 5px;"><a href="https://maoleng.dev">maoleng.dev</a></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            <img src="{{getConfigValue('mail_notification.footer_logo')}}" alt="" style="width: 125px; margin: 30px auto 0;">
        </td>
    </tr>
</table>
