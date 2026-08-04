<?php

namespace App\Http\Controllers\Legatra;

use App\Helpers\GenerateQR;
use App\Helpers\MyHelper;
use App\Http\Controllers\Controller;
use App\Models\Table\QRDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Matrix\Decomposition\QR;
use setasign\Fpdi\Fpdi;
use Vinkla\Hashids\Facades\Hashids;

class QRDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {

            $keyword = $request->get('keyword');

            if($keyword != null){
                $qrDocuments = QRDocument::where('no_document', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%")
                ->paginate(8);
            }else{
                $qrDocuments = QRDocument::paginate(8);
            }
            return view('main.qr-document.index', [
                'qrDocuments' => $qrDocuments,
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error'.$e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('main.qr-document.create');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'no_document' => 'required',
                'string',
                'max:255',
                'date_document' => 'required',
                'date',
                'description' => 'required',
                'string',
                'receipent' => 'required',
                'string',
                'no_materai' => 'required',
                'string',
                'max:255',
                'user_sign' => 'required',
                'string',
            ]);

            if ($validasi->fails()) {
                return redirect()->route('qr-document.create')->withErrors($validasi)->withInput();
            }

            // dd($request->file('document'));

            // 2. Store file 1 ke storage local / api attachment lalu ambil id attachment
            // $filePath = $request->file('document')->store('documents');
            $fileName = time() . '-' . $request->file('document')->getClientOriginalName();
            request()->file('document')->move(public_path('storage/document/qr'), $fileName);
            $filePath = 'document/qr/' . $fileName;

            // 3. Inisialisasi untuk file 1 dan 2
            $originalFileName = $request->file('document')->getClientOriginalName();

            // Pisahkan nama file dan ekstensinya
            $fileInfo = pathinfo($originalFileName);
            $baseName = $fileInfo['filename']; // Nama file tanpa ekstensi
            $extension = $fileInfo['extension']; // Ekstensi file

            // Buat nama file baru dengan format yang diinginkan
            $outputFileName = time() . '-' . $baseName . '-Final.' . $extension;

            $outputFilePath = 'output/document/qr/' . $outputFileName;

            // 4. Generate QR Code untuk ditempel di file 2
            $date_uploaded = now()->format('Y-m-d');

            $data = [
                'no_document' => $request->no_document,
                'date_document' => $request->date_document,
                'no_materai' => $request->no_materai,
                'date_uploaded' => $date_uploaded,
            ];

            $hashData = md5(json_encode($data));

            $qrCode = GenerateQR::generate($hashData, 800);

            // 5. Tempel QR Code di file baru
            try {
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile('storage/' . $filePath);
            } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
                // Jika terjadi kesalahan saat membuka file PDF, redirect pengguna kembali ke halaman create
                Storage::disk('public')->delete([$qrCode, $filePath]);
                return redirect()->route('qr-document.create')->with('error', 'PDF files are not supported. Please upload a valid PDF file.');
            } catch (\Exception $e) {
                Storage::disk('public')->delete([$qrCode, $filePath]);
                return redirect()->route('qr-document.create')->with('error', 'There is an error. Check the uploaded file, it must be in pdf format');
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);
                if ($pageNo === $pageCount) {
                    $pageWidth = $pdf->GetPageWidth();
                    $qrCodeWidth = 15;
                    $qrCodeHeight = 15;
                    $xPosition = ($pageWidth - $qrCodeWidth) / 2;

                    $pdf->setXY($xPosition, 250);
                    $pdf->Image(public_path('storage/' . $qrCode), $xPosition, $pdf->GetY(), $qrCodeWidth, $qrCodeHeight, 'PNG');

                    $text = 'Scan here to check more information about this document';
                    $text2 = 'Or you can request validation this document from ' . env('APP_URL');
                    $pdf->SetXY($xPosition, $pdf->GetY() + 13);
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->Cell($qrCodeWidth, 10, $text, 0, 0, 'C');
                    $verticalGap = 2; // Ubah sesuai kebutuhan
                    $pdf->SetXY($xPosition, $pdf->GetY() + $verticalGap);
                    $pdf->Cell($qrCodeWidth, 10, $text2, 0, 0, 'C');
                }
            }

            $storagePath = public_path('storage/' . $outputFilePath);

            if (!file_exists(dirname($storagePath))) {
                mkdir(dirname($storagePath), 0777, true);
            }

            $pdf->Output($storagePath, 'F');

            $storeData = [
                'no_document' => $request->no_document,
                'date_document' => $request->date_document,
                'description' => $request->description,
                'receipent' => $request->receipent,
                'no_materai' => $request->no_materai,
                'date_uploaded' => $date_uploaded,
                'qrcode_url' => $qrCode,
                'user_id' => Auth::user()->id,
                'user_sign' => $request->user_sign,
                'id_attachment' => $filePath,
                'id_document_final' => $outputFilePath,
                'doc_hash' => $hashData,
            ];

            QRDocument::create($storeData);

            return redirect()->route('qr-document.index')->with('success', 'Data successfully stored');
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QRDocument  $qRDocument
     * @return \Illuminate\Http\Response
     */
    public function show(QRDocument $qRDocument, $id)
    {
        try {
            $id = Hashids::decode($id);
            $document = QRDocument::findOrFail($id[0]);
            return view('main.qr-document.show', [
                'document' => $document,
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QRDocument  $qRDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(QRDocument $qRDocument, $id)
    {
        try {
            $id = Hashids::decode($id);
            $document = QRDocument::findOrFail($id[0]);
            return view('main.qr-document.edit', [
                'document' => $document,
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QRDocument  $qRDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QRDocument $qRDocument, $id)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'no_document' => ['required', 'string', 'max:255'],
                'date_document' => ['required', 'date'],
                'description' => ['required', 'string'],
                'receipent' => ['required', 'string'],
                'no_materai' => ['required', 'string', 'max:255'],
                'user_sign' => ['required', 'string'],
            ]);

            if ($validasi->fails()) {
                return redirect()->route('qr-document.edit')->withErrors($validasi)->withInput();
            }

            $qRDocument = QRDocument::findOrFail($id);

            $qrCodeUrl = $qRDocument->qrcode_url;
            $idDocumentFinal = $qRDocument->id_document_final;
            $idAttachment = $qRDocument->id_attachment;
            $pageCount = 0;
            $pdf = new Fpdi();

            if ($request->hasFile('document')) {
                try {
                    $fileName = time() . '-' . $request->file('document')->getClientOriginalName();
                    request()->file('document')->move(public_path('storage/document/qr'), $fileName);
                    $filePath = 'document/qr/' . $fileName;
                    $pageCount = $pdf->setSourceFile('storage/' . $filePath);
                    $originalFileName = $request->file('document')->getClientOriginalName();
                } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
                    // Jika terjadi kesalahan saat membuka file PDF, redirect pengguna kembali ke halaman create
                    Storage::disk('public')->delete([$filePath]);
                    return redirect()->route('qr-document.edit', $qRDocument)->with('error', 'PDF files are not supported. Please upload a valid PDF file.');
            } catch (\Exception $e) {
                    Storage::disk('public')->delete([$filePath]);
                    return redirect()->route('qr-document.edit', $qRDocument)->with('error', 'There is an error. Check the uploaded file, it must be in pdf format');
                }
                Storage::disk('public')->delete([$qrCodeUrl, $idDocumentFinal, $idAttachment]);
            } else {
                Storage::disk('public')->delete([$qrCodeUrl, $idDocumentFinal]);
                $filePath = $idAttachment;
                $pageCount = $pdf->setSourceFile('storage/' . $filePath);
                $originalFileName = preg_replace('/.*\.\d+\./', '', $filePath);
            }

            $fileInfo = pathinfo($originalFileName);
            $baseName = $fileInfo['filename']; // Nama file tanpa ekstensi
            $extension = $fileInfo['extension']; // Ekstensi file

            // // Buat nama file baru dengan format yang diinginkan
            $outputFileName = time() . '-' . $baseName . '-Final.' . $extension;

            $outputFilePath = '/output/document/qr/' . $outputFileName;

            $data = [
                'no_document' => $request->no_document,
                'date_document' => $request->date_document,
                'no_materai' => $request->no_materai,
                'date_uploaded' => $qRDocument->date_uploaded,
            ];

            $hashData = md5(json_encode($data));
            $qrCode = GenerateQR::generate($hashData, 800);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);
                if ($pageNo === $pageCount) {
                    $pageWidth = $pdf->GetPageWidth();
                    $qrCodeWidth = 15;
                    $qrCodeHeight = 15;
                    $xPosition = ($pageWidth - $qrCodeWidth) / 2;

                    $pdf->setXY($xPosition, 250);
                    $pdf->Image(public_path('storage/' . $qrCode), $xPosition, $pdf->GetY(), $qrCodeWidth, $qrCodeHeight, 'PNG');

                    $text = 'Scan here to check more information about this document';
                    $text2 = 'Or you can request validation this document from ' . env('APP_URL');
                    $pdf->SetXY($xPosition, $pdf->GetY() + 13);
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->Cell($qrCodeWidth, 10, $text, 0, 0, 'C');
                    $verticalGap = 2; // Ubah sesuai kebutuhan
                    $pdf->SetXY($xPosition, $pdf->GetY() + $verticalGap);
                    $pdf->Cell($qrCodeWidth, 10, $text2, 0, 0, 'C');
                }
            }

            $storagePath = public_path('storage' . $outputFilePath);

            if (!file_exists(dirname($storagePath))) {
                mkdir(dirname($storagePath), 0777, true);
            }

            $pdf->Output($storagePath, 'F');

            $storeData = [
                'no_document' => $request->no_document,
                'date_document' => $request->date_document,
                'description' => $request->description,
                'receipent' => $request->receipent,
                'no_materai' => $request->no_materai,
                'qrcode_url' => $qrCode,
                'user_id' => Auth::user()->id,
                'user_sign' => $request->user_sign,
                'id_attachment' => $filePath,
                'id_document_final' => $outputFilePath,
                'doc_hash' => $hashData,
            ];

            $qRDocument->update($storeData);

            return redirect()->route('qr-document.index')->with('success', 'Data updated successfully');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QRDocument  $qRDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(QRDocument $qRDocument, $id)
    {
        try {
            
            $qRDocument = QRDocument::findOrFail($id);
            $qrCodeUrl = $qRDocument->qrcode_url;
            $idDocumentFinal = $qRDocument->id_document_final;
            $idAttachment = $qRDocument->id_attachment;
            Storage::disk('public')->delete([$qrCodeUrl, $idDocumentFinal, $idAttachment]);
            QRDocument::destroy($qRDocument->id);
            return redirect()->route('qr-document.index')->with('success', 'Data deleted successfully');
        
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error'.$e);
        }
    }

    public function checkDocument(Request $request)
    {
        $hash = $request->get('key');
        $document = QRDocument::where('doc_hash', $hash)->first();

        if($document == null){
            return view('main.qr-document.checkdocument', [
                'document' => $document,
                'title' => 'Document Lookup',
                'status' => 'failed'
            ]);
        }else{
            return view('main.qr-document.checkdocument', [
                'title' => 'Document Lookup',
                'document' => $document,
                'status' => 'success'
            ]);
        }
    }
}
