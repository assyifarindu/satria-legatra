<?php

namespace App\Http\Controllers\Legatra\TSP;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Table\TspRequestDocumentFile;
use App\Models\Table\TspRequestDocumentFileDownloads;

class DownloadFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != NULL) {
                // return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    /**
     * Download file with timestamp footer
     * @param int $fileId
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($fileId)
    {
        try {
            $user = Auth::user();
            $file = TspRequestDocumentFile::findOrFail($fileId);

            if (!file_exists($file->file_path)) {
                return redirect()->route('tsp.request-document')->with('error', 'File not found.');
            }

            date_default_timezone_set('Asia/Jakarta');
            $timestampText = 'Diunduh oleh ' . $user->name . ' pada ' . date('d-m-Y H:i:s') . ' WIB';

            // 1. Tentukan path skrip Python dan file output sementara
            $scriptPath = storage_path('app/scripts/add_timestamp.py');
            $outputPath = storage_path('app/temp/'. $file->name);

            // Path ke python.exe di dalam venv (Sesuaikan dengan OS Anda)
            // Untuk Windows (XAMPP / Local):
            $pythonExe = base_path('venv/Scripts/python.exe');

            // Pastikan folder temp ada
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // 2. Jalankan skrip Python dengan escapeshellarg untuk keamanan
            $pythonCommand = escapeshellarg($pythonExe) . " " . 
                             escapeshellarg($scriptPath) . " " . 
                             escapeshellarg($file->file_path) . " " . 
                             escapeshellarg($outputPath) . " " . 
                             escapeshellarg($timestampText);
            $output = [];
            $resultCode = 0;
            exec($pythonCommand . " 2>&1", $output, $resultCode);

            // Cek apakah eksekusi python sukses
            if ($resultCode !== 0 || !file_exists($outputPath)) {
                Log::error("Python PDF Error: " . implode("\n", $output));
                return redirect()->route('tsp.request-document')->with('error', 'Gagal memproses file PDF.');
            }

            // 3. Simpan data download user ke database
            TspRequestDocumentFileDownloads::create([
                'request_document_file_id' => $file->id,
                'download_by' => $user->id,
                'download_at' => now(),
                'created_by' => $user->id,
                'created_at' => now()
            ]);

            // 4. Kirim file hasil modifikasi ke user untuk diunduh, lalu hapus file temp setelahnya
            return response()->download($outputPath, $file->file_name, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);

        } catch( \Exception $e) {
            dd($e);
            return redirect()->route('tsp.request-document')->with('error', 'Failed to download file. Please try again.');
        }
    }
}
