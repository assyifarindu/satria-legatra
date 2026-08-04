<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MyHelper
{
    public static function ubahFormatTanggal($tanggal)
    {
        $carbonDate = Carbon::parse($tanggal);
        return $carbonDate->format('d F Y');
    }

    public static function ubahFormatTimestamp($timestamp)
    {
        $carbonDate = Carbon::parse($timestamp);
        return $carbonDate->format('d F Y, H:i:s');
    }

    public static function potongString($string)
    {
        $result = Str::limit(strip_tags($string), 50);
        return $result;
    }

    public static function potongRequestUrl($currentUrl)
    {
        // Menggunakan parse_url untuk membagi URL menjadi komponen-komponennya
        $parsedUrl = parse_url($currentUrl);

        // Mengambil bagian path dari URL
        $path = $parsedUrl['path'];

        // Menghapus karakter slash dari awal string jika ada
        $path = ltrim($path, '/');

        // Mengembalikan hanya path dari URL tanpa slash di awal
        return $path;
    }

    public static function sendEmailResult($email, $details)
    {
        try {
            Mail::to($email)->send(new \App\Mail\DocumentResponseMail($details));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
