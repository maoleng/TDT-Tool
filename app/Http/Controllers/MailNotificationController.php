<?php

namespace App\Http\Controllers;

use App\Models\File;
use Exception;
use Illuminate\Contracts\View\View as ViewReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class MailNotificationController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'Trang chủ');
        View::share('route', 'index');
    }

    public function mailNotification(): ViewReturn
    {
        return view('app.control_panel.mail_notification', [
            'breadcrumb' => 'Nhận'
        ]);
    }

    public function handleImage($mail_content, $notification)
    {
        preg_match_all('/data:image\/[A-Za-z-]+;base64.[A-Za-z+\/0-9=]+/', $mail_content, $matches, PREG_OFFSET_CAPTURE);
        $images = $matches[0];
        if (isset($images)) {
            foreach ($images as $image) {
                $content = base64_decode(explode(';base64,', $image[0])[1]);
                $mime = $this->getMimeType($image[0]);
                if (empty($mime)) {
                    throw new \RuntimeException('Sai thể loại ảnh');
                }
                $path = 'notification-' . $notification->notification_id . '/'. Str::random(15) . '.' . $mime;
                Storage::disk('google')->put($path, $content);
                $source = Storage::disk('google')->url($path);
                File::query()->create([
                    'source' => $source,
                    'path' => $path,
                    'size' => strlen($image[0]),
                    'notification_id' => $notification->id,
                ]);
                $mail_content = str_replace($image[0], $source, $mail_content);
            }
        }

        return $mail_content;
    }

    public function getMimeType($base64): ?string
    {
        if (str_starts_with($base64, 'data:image/bmp')) {
            return 'bmp';
        }
        if (str_starts_with($base64, 'data:image/jpeg')) {
            return 'jpg';
        }
        if (str_starts_with($base64, 'data:image/png')) {
            return 'png';
        }
        if (str_starts_with($base64, 'data:image/x-icon')) {
            return 'ico';
        }
        if (str_starts_with($base64, 'data:image/webp')) {
            return 'webp';
        }
        if (str_starts_with($base64, 'data:image/gif')) {
            return 'gif';
        }

        return null;
    }

}
