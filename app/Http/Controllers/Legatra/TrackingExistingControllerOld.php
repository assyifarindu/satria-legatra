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
use App\Models\Table\Alert as TableAlert;
use Carbon\Carbon;

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
    public function index(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('tracking-existing')->r == 1) {

                $filter = '';
                $filter_position = [];

                if (isset($request->filter_position)) {
                    if ($request->filter_position == 'legal') {
                        $filter_position = [1, 2, 4];
                    }
                    if ($request->filter_position == 'user') {
                        $filter_position = [3];
                    }

                    if ($request->filter_position != 'all') {
                        $tracking = RequestExisting::where('is_deleted', false)->whereIn('status', $filter_position)->where('is_cancel', false)->orderBy('created_at', 'desc')->get();
                    } else {
                        if ($request->filter != 'cancel' && $request->filter != 'all') {
                            $tracking = RequestExisting::where('is_deleted', false)->where('status', $request->filter)->where('is_cancel', false)->orderBy('created_at', 'desc')->get();
                        } elseif ($request->filter == 'cancel') {
                            $tracking = RequestExisting::where('is_deleted', false)->where('is_cancel', true)->orderBy('created_at', 'desc')->get();
                        } else {
                            $tracking = RequestExisting::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
                        }

                        $filter = $request->filter;
                    }

                    $filter = $request->filter;
                    $filter_position_sel = $request->filter_position;
                } else {
                    $tracking = RequestExisting::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
                    $filter = 'all';
                    $filter_position_sel = 'all';
                }

                $data = [
                    'request' => $tracking,
                    'filter' => $filter,
                    'filter_position' => $filter_position_sel
                ];

                return view('main.tracking_existing.index')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
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

            // File
            $request->validate([
                'file' => 'required',
            ]);

            $fileName = 'Document-' . time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('upload/document/request_existing'), $fileName);

            $document = RequestExistingDocument::findOrFail($document_id);

            // delete old file
            if ($document->file != null) {
                $docfile = public_path('upload/document/request_existing/') . $document->file;
                unlink($docfile);
            }

            $document->file = $fileName;

            RequestExisting::where('id', $id)->update(['is_pdp' => $pdp]);

             //Expired Document
             if($expired !== null){
                $alert = TableAlert::where('id', $expired)->first();
                $duration = $alert->duration;
                $expired_date = Carbon::now()->addDays($duration);  
                $expired_date_format = Carbon::now()->addDays($duration)->format('Y-m-d');
                $document->expired_date = $expired_date_format;
            }

            $document->update();

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking-existing.show', Hashids::encode($id));
        } catch (Exception $e) {
            dd($e);
            $this->ErrorLog($e);
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
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
