<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\RequestExisting;
use App\Models\Table\RequestExistingActivity;
use App\Models\Table\RequestExistingDocument;
use App\Models\Table\RequestExistingHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use App\Exports\ExportExistingRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Table\Alert as TableAlert;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Table\Company;



class TrackingExistingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('tracking-existing') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            if ($this->PermissionActionMenu('tracking-existing')->r == 1) {
                $id = Auth::user()->id;
                $user = User::findOrFail($id);
                $company = $user->companyid;

                $data = [
                    'request' => RequestExisting::join('satria.users', 'request_existings.created_by', '=', 'satria.users.id')
                    ->where('is_deleted', false)->where('satria.users.companyid', $company)->orderBy('request_existings.created_at', 'desc')
                    ->select('request_existings.*')->get()
                ];

                return view('main.tracking_existing.index')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $id = $request->request_existing_id;
            $document_id = $request->request_document_id;
            $pdp = $request->is_pdp;
            $expired = $request->expired_date; 

            $expired_date_format = null;
            if ($expired !== null) {
                $alert = TableAlert::where('id', $expired)->first();
                $duration = $alert->duration;
                $expired_date_format = Carbon::now()->addDays($duration)->format('Y-m-d');
            }

            // File
            $request->validate([
                'file' => 'required',
            ]);

            $files = $request->file('file');

            // $fileName = 'Document-' . time() . '.' . request()->file->getClientOriginalExtension();
            // request()->file->move(public_path('upload/document/request_existing'), $fileName);

            $document = RequestExistingDocument::findOrFail($document_id);

            // delete old file
            // if ($document->file != null) {
            //     $docfile = public_path('upload/document/request_existing/') . $document->file;
            //     unlink($docfile);
            // }

            // if ($document->file && file_exists(public_path('upload/document/request_existing/') . $document->file)) {
            //     unlink(public_path('upload/document/request_existing/') . $document->file);
            // }

            // $document->file = $fileName;

            foreach ($files as $index => $uploadedFile) {
                $fileName = 'Document-' . time() . '-' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move(public_path('upload/document/request_existing'), $fileName);

                if ($index === 0) {
                    // 🛠 Update ke file pertama
                    // Hapus file lama jika ada
                    if ($document->file && file_exists(public_path('upload/document/request_existing/' . $document->file))) {
                        unlink(public_path('upload/document/request_existing/' . $document->file));
                    }

                    $document->file = $fileName;
                    if ($expired_date_format) {
                        $document->expired_date = $expired_date_format;
                    }
                    
                    $document->update();
                } else {
                    // ➕ Create baru untuk file kedua dan seterusnya
                    RequestExistingDocument::create([
                        'request_existing_id' => $id,
                        'title' => $document->title,
                        'file' => $fileName,
                        'expired_date' => $expired_date_format,
                        'created_at' => now(),
                    ]);
                }
            }
            

            RequestExisting::where('id', $id)->update(['is_pdp' => $pdp]);

             //Expired Document
            //  if($expired !== null){
            //     $alert = TableAlert::where('id', $expired)->first();
            //     $duration = $alert->duration;
            //     $expired_date = Carbon::now()->addDays($duration);  
            //     $expired_date_format = Carbon::now()->addDays($duration)->format('Y-m-d');
            //     $document->expired_date = $expired_date_format;
            // }

            // $document->update();

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking-existing.show', Hashids::encode($id));
        } catch (Exception $e) {
            // dd($e);
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // if($this->PermissionActionMenu('tracking-existing')->r==1) {
            $id = Hashids::decode($id);

            $data = array(
                'tracking' => RequestExisting::findOrFail($id['0']),
                'document' => RequestExistingActivity::where('request_existing_id', $id['0'])->get(),
                'expired' => TableAlert::get()
            );

            clickedNotification(Auth::user()->id, $id['0'], 'Request Existing Document');
            clickedNotification(Auth::user()->id, $id['0'], 'Your document getting feedback');
            clickedNotification(Auth::user()->id, $id['0'], 'Your document has approved');

            return view('main.tracking_existing.show')->with('data', $data);
            // } else {
            //     return redirect()->back()->with('error', 'Akses Ditolak!');
            // }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function historyProcess($id)
    {
        try {
            $doc_id = $id;

            $document = RequestExistingHistory::where('request_existing_id', $doc_id)->orderBy('created_at', 'asc')->get();
            $no = 1;
            $date_before = '1990-02-01';
            $response = "<table class='table table-striped'>";
            $response .= "<thead><tr><th>No</th><th>Process Name</th><th>Oleh</th><th>Tanggal</th><th>SLA</th></tr></thead><tbody>";
            foreach ($document as $item) {
                $response .= "<tr>";
                $response .= "<td>" . $no . "</td>";
                $response .= "<td>" . $item->step_name . "</td>";
                $response .= "<td>" . getUserName($item->created_by)->name . "</td>";
                $response .= "<td>" . formatDate($item->created_at) . "</td>";
                if ($date_before == '1990-02-01') {
                    $response .= "<td> 0 Hari</td>";
                } else {
                    $start = strtotime($date_before);
                    $end = strtotime($item->created_at);
                    $datediff = $end - $start;

                    $response .= "<td>" . round($datediff / (60 * 60 * 24)) . " Hari</td>";
                }

                $response .= "</tr>";

                $no++;
                $date_before = $item->created_at;
            }
            $response .= "</tbody></table>";

            return response()->json($response);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $request_document = RequestExisting::findOrFail($request->id_cancel);
            $request_document->is_cancel = true;
            $request_document->update();

            Alert::success('Request Has Cancelled', 'Success Message');
            return redirect()->route('tracking-existing.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function processStep(Request $request)
    {
        try {
            $id = $request->id;

            $request_existing = RequestExisting::findOrFail($id);
            $request_existing->status = 2;
            $request_existing->position = $request_existing->created_by;
            $request_existing->update();

            // History
            $dataHistory = array(
                'request_existing_id' => $id,
                'step' => 2,
                'created_by' => Auth::user()->id,
                'step_name' => 'Legal upload document'
            );

            RequestExistingHistory::create($dataHistory);

            // Notification
            addNotification($request_existing->created_by, 'request-existing.show', 'Legal upload request existing document', $id);

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking-existing.show', Hashids::encode($id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function processStepRequester(Request $request)
    {
        try {
            $id = $request->id;

            $request_existing = RequestExisting::findOrFail($id);
            $request_existing->status = 3;
            $request_existing->position = $request_existing->created_by;
            $request_existing->update();

            // History
            $dataHistory = array(
                'request_existing_id' => $id,
                'step' => 3,
                'created_by' => Auth::user()->id,
                'step_name' => 'Legal send document to requester'
            );

            RequestExistingHistory::create($dataHistory);

            // Notification
            addNotification($request_existing->created_by, 'request-existing.show', 'Legal upload request existing document', $id);

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking-existing.show', Hashids::encode($id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function export()
    {
        $date = date('Y-m-d');
        return Excel::download(new ExportExistingRequest, 'ExistingRequest_' . $date . '.xlsx');
    }

}
