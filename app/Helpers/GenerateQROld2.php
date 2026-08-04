<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class GenerateQR {
    public static function generate($key, $size=200){
        $pathKey = env('APP_URL').'/document/look?key='.$key;
        $output_file = 'output/qrcodes/QR-' . uniqid() . '.png';

        if (!Storage::disk('public')->exists('output/qrcodes')) {
            Storage::disk('public')->makeDirectory('output/qrcodes');
        }

        $qrCode = new QrCode($pathKey);
        
        $output = new Output\Png();
        $data = $output->output($qrCode, 200, [255, 255, 255], [0, 0, 0]);
        // Storage::disk('public')->put($output_file, $data);
        $filePath = public_path('storage/'.$output_file);
        // dd($filePath);
        file_put_contents($filePath, $data);

        // Storage::disk('public')->put($output_file, $data);
        
        return $output_file;
    }
}