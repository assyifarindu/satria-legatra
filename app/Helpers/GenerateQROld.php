<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class GenerateQR
{
    public static function generate($key, $size = 200)
    {

        $pathKey = env('APP_URL') . '/document/look?key=' . $key;
        $output_file = 'output/qrcodes/QR-' . uniqid() . '.png';

        $qrCode = new QrCode($pathKey);

        $output = new Output\Png();
        $data = $output->output($qrCode, 200, [255, 255, 255], [0, 0, 0]);

        $filePath = public_path('storage/' . $output_file);
        file_put_contents($filePath, $data);

        return $output_file;
    }
}
