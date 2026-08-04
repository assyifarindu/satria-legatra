<?php

namespace App\Http\Controllers\Legatra;

use App\Helpers\GenerateQR;
use App\Http\Controllers\Controller;
use App\Models\Table\GenerateNumber;
use App\Models\Table\QRDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

            $qrDocuments = QRDocument::all();

            // $qrDocuments = QRDocument::with('generateNumber')->all();

            // dd($qrDocuments);

            return view('main.qr-document.index', [
                'qrDocuments' => $qrDocuments,
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error' . $e);
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
            $documentNumbers = GenerateNumber::leftJoin('qr_documents', 'generate_numbers.id', '=', 'qr_documents.no_document')
            ->whereNull('qr_documents.no_document')
            ->select('generate_numbers.*')
            ->get();
            return view('main.qr-document.create', [
                'documentNumbers' => $documentNumbers,
            ]);
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
            ]);

            if ($validasi->fails()) {
                return redirect()->route('qr-document.create')->withErrors($validasi)->withInput();
            }

            $generateNumber = GenerateNumber::where("id", $request->no_document)->first();

            $countQRDocument = QRDocument::where('no_document', $request->no_document)->count();
            if($countQRDocument > 0){
                return redirect()->route('qr-document.create')->with('error', 'This document already generated, please try again with another number document');
            }

            if($request->reupload != null){
               
                $fileName = time() . '-' . $request->file('reupload')->getClientOriginalName();
                request()->file('reupload')->move(public_path('storage/document/numbers'), $fileName);
                $filePath = '/document/numbers/' . $fileName;
                Storage::disk('public')->delete([$generateNumber->file]);
                $generateNumber->update([
                    'file' => $filePath
                ]);

            }else{
                $filePath = $request->input('file');
            }

            $originalFileName = preg_replace('/.*\.\d+\./', '', $filePath);
            $fileInfo = pathinfo($originalFileName);
            $baseName = $fileInfo['filename']; // Nama file tanpa ekstensi
            $extension = $fileInfo['extension']; // Ekstensi file

            // Buat nama file baru dengan format yang diinginkan
            $outputFileName = time() . '-' . $baseName . '-Final.' . $extension;
            $outputFilePath = '/output/document/qr/' . $outputFileName;

            // 4. Generate QR Code untuk ditempel di file 2
            $date_uploaded = now()->format('Y-m-d');

            $data = [
                'no_document' => $generateNumber->document_number,
                'date_document' => $request->date_document,
                'no_materai' => $request->no_materai,
                'date_uploaded' => $date_uploaded,
                'time' => now(),
            ];

            $hashData = md5(json_encode($data));
            $qrCode = GenerateQR::generate($hashData, 800);
            // 5. Tempel QR Code di file baru
            try {
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile('storage/' . $filePath);
            } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
                return redirect()->route('qr-document.create')->with('error', $e->getMessage());
            } catch (\Exception $e) {
                return redirect()->route('qr-document.create')->with('error', 'There is an error. Check the uploaded file, it must be in pdf format');
            }

            $stampX = ($request->stampX / 1.5);
            $stampY = ($request->stampY / 1.5);
            $canvasHeight = $request->canvasHeight / 1.5;
            $canvasWidth = $request->canvasWidth / 1.5;
            $pageNumber = $request->pageNumber;

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L');
                } else {
                    $pdf->AddPage();
                }
                $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);
                if ($pageNo == $pageNumber) {
                    $qrCodeWidth = 20.46;
                    $qrCodeHeight = 20.46;

                    $widthDiffPercent = ($canvasWidth - $size['width']) / $canvasWidth * 100;
                    $heightDiffPercent = ($canvasHeight - $size['height']) / $canvasHeight * 100;
        
                    $realXPosition = $stampX - ($widthDiffPercent * $stampX / 100);
                    $realYPosition = $stampY - ($heightDiffPercent * $stampY / 100);
        
                    $pdf->Image(public_path('storage/' . $qrCode), $realXPosition, $realYPosition, $qrCodeWidth, $qrCodeHeight, 'PNG');
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
                'user_sign' => $request->user_sign ?? '',
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
        // try {
        //     $id = Hashids::decode($id);
        //     $document = QRDocument::findOrFail($id[0]);
        //     $documentNumbers = GenerateNumber::all();

        //     return view('main.qr-document.edit', [
        //         'document' => $document,
        //         'documentNumbers' => $documentNumbers,
        //     ]);
        // } catch (Exception $e) {
        //     $this->ErrorLog($e);
        //     return redirect()->back()->with('error', 'Error Request, Exception Error ');
        // }
        return redirect()->back()->with('error', 'Connot access this page' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QRDocument  $qRDocument
     * @return \Illuminate\Http\Response
     */

    // public function update(Request $request, QRDocument $qRDocument, $id)
    // {
    //     try {
    //         $validasi = Validator::make($request->all(), [
    //             'no_document' => ['required', 'string', 'max:255'],
    //             'date_document' => ['required', 'date'],
    //             'description' => ['required', 'string'],
    //             'receipent' => ['required', 'string'],
    //             'no_materai' => ['required', 'string', 'max:255'],
    //             'user_sign' => ['required', 'string'],
    //         ]);

    //         if ($validasi->fails()) {
    //             return redirect()->route('qr-document.edit')->withErrors($validasi)->withInput();
    //         }

    //         $qRDocument = QRDocument::findOrFail($id);

    //         $qrCodeUrl = $qRDocument->qrcode_url;
    //         $idDocumentFinal = $qRDocument->id_document_final;
    //         $idAttachment = $qRDocument->id_attachment;
    //         $pageCount = 0;
    //         $pdf = new Fpdi();

    //         $filePath = $request->input('file');

    //         try {
    //             $pageCount = $pdf->setSourceFile('storage/' . $filePath);
    //             Storage::disk('public')->delete([$qrCodeUrl, $idDocumentFinal]);
    //             $filePath = $idAttachment;
    //             $pageCount = $pdf->setSourceFile('storage/' . $filePath);
    //             $originalFileName = preg_replace('/.*\.\d+\./', '', $filePath);
    //         } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
    //             return redirect()->route('qr-document.edit', $qRDocument)->with('error', 'PDF files are not supported. Please upload a valid PDF file.');
    //         } catch (\Exception $e) {
    //             return redirect()->route('qr-document.edit', $qRDocument)->with('error', 'There is an error. Check the uploaded file, it must be in pdf format');
    //         }

    //         $fileInfo = pathinfo($originalFileName);
    //         $baseName = $fileInfo['filename']; // Nama file tanpa ekstensi
    //         $extension = $fileInfo['extension']; // Ekstensi file

    //         // // Buat nama file baru dengan format yang diinginkan
    //         $outputFileName = time() . '-' . $baseName . '-Final.' . $extension;

    //         $outputFilePath = '/output/document/qr/' . $outputFileName;

    //         $data = [
    //             'no_document' => $request->no_document,
    //             'date_document' => $request->date_document,
    //             'no_materai' => $request->no_materai,
    //             'date_uploaded' => $qRDocument->date_uploaded,
    //         ];

    //         $hashData = md5(json_encode($data));
    //         $qrCode = GenerateQR::generate($hashData, 800);

    //         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //             $pdf->AddPage();
    //             $templateId = $pdf->importPage($pageNo);
    //             $size = $pdf->getTemplateSize($templateId);
    //             $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);
    //             if ($pageNo === $pageCount) {
    //                 $pageWidth = $pdf->GetPageWidth();
    //                 $qrCodeWidth = 15;
    //                 $qrCodeHeight = 15;
    //                 $xPosition = ($pageWidth - $qrCodeWidth) / 2;

    //                 $pdf->setXY($xPosition, 250);
    //                 $pdf->Image(public_path('storage/' . $qrCode), $xPosition, $pdf->GetY(), $qrCodeWidth, $qrCodeHeight, 'PNG');

    //                 $text = 'Scan here to check more information about this document';
    //                 $text2 = 'Or you can request validation this document from ' . env('APP_URL');
    //                 $pdf->SetXY($xPosition, $pdf->GetY() + 13);
    //                 $pdf->SetFont('Arial', '', 6);
    //                 $pdf->Cell($qrCodeWidth, 10, $text, 0, 0, 'C');
    //                 $verticalGap = 2; // Ubah sesuai kebutuhan
    //                 $pdf->SetXY($xPosition, $pdf->GetY() + $verticalGap);
    //                 $pdf->Cell($qrCodeWidth, 10, $text2, 0, 0, 'C');
    //             }
    //         }

    //         $storagePath = public_path('storage' . $outputFilePath);

    //         if (!file_exists(dirname($storagePath))) {
    //             mkdir(dirname($storagePath), 0777, true);
    //         }

    //         $pdf->Output($storagePath, 'F');

    //         $storeData = [
    //             'no_document' => $request->no_document,
    //             'date_document' => $request->date_document,
    //             'description' => $request->description,
    //             'receipent' => $request->receipent,
    //             'no_materai' => $request->no_materai,
    //             'qrcode_url' => $qrCode,
    //             'user_id' => Auth::user()->id,
    //             'user_sign' => $request->user_sign,
    //             'id_attachment' => $filePath,
    //             'id_document_final' => $outputFilePath,
    //             'doc_hash' => $hashData,
    //         ];

    //         $qRDocument->update($storeData);

    //         return redirect()->route('qr-document.index')->with('success', 'Data updated successfully');
    //     } catch (Exception $e) {
    //         $this->ErrorLog($e);
    //         return redirect()->back()->with('error', 'Error Request, Exception Error');
    //     }
    // }

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
            Storage::disk('public')->delete([$qrCodeUrl, $idDocumentFinal]);
            QRDocument::destroy($qRDocument->id);
            return redirect()->route('qr-document.index')->with('success', 'Data deleted successfully');

        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error' . $e);
        }
    }

    public function checkDocument(Request $request)
    {
        $hash = $request->get('key');
        $document = QRDocument::where('doc_hash', $hash)->first();

        if ($document == null) {
            return view('main.qr-document.checkdocument', [
                'document' => $document,
                'title' => 'Document Lookup',
                'status' => 'failed'
            ]);
        } else {
            return view('main.qr-document.checkdocument', [
                'title' => 'Document Lookup',
                'document' => $document,
                'status' => 'success'
            ]);
        }
    }

    public function getDocumentGenerateNumber($idGenerateNumber)
    {
        $generateDocument = GenerateNumber::where('id', $idGenerateNumber)->first();
        // dd($generateDocument);
        return response()->json($generateDocument);
    }
}
