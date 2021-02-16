<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public static function uf_base64($file, $path)
    {
        $check_base64 = strrpos($file, 'base64');

        if ($check_base64 > 0) {
            $explode = explode(",", $file);

            $decoded_file = base64_decode($explode[1]);

            $file_extension = self::uf_get_file_extension($explode[0]);

            $filename = date('dmyYHis') . '.' . $file_extension;

            Storage::disk('public')->put($path . $filename, $decoded_file, 'public');
            $url = Storage::url($path . $filename);
            return basename($url);
        } else {
            return $file;
        }
    }

    public static function uf_get_file_extension($info)
    {
        $mime = str_replace(';base64', '', $info);
        $mime = str_replace('data:', '', $mime);

        $extension_arr = [
            "image/jpeg" => "jpg",
            "image/png" => "png"
        ];

        return $extension_arr[$mime];
    }
}
