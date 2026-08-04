<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\BaseDocument;
use App\Models\Table\Notification;
use App\Models\Table\Pic;
use App\Models\Table\RequestExisting;
use App\Models\Table\RequestExistingActivity;
use App\Models\Table\RequestExistingDocument;
use App\Models\Table\RequestExistingHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Table\Company;

class RequestExistingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if (Auth::user()->role_id != NULL) {
        //         return redirect('/')->with('error', 'Access denied!');
        //     }
        //     return $next($request);
        // });
    }
    public function index()
    {
        try {
            $data = [
                'request' => RequestExisting::where('created_by', Auth::user()->id)->where('is_deleted', false)->orderBy('created_at', 'desc')->get()
            ];

            return view('user.request_existing.index')->with('data', $data);
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
        try {
            $user = User::where('id', Auth::user()->id)->first();
            $company_name = $user->company_name;
            $company = Company::where('name', $company_name)->first();

            $data = array(
                'document' => BaseDocument::where('status', 1)->orderBy('description', 'asc')->get(),
                'pic' => Pic::all()
            );
            return view('user.request_existing.create')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
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

            $pic = Pic::findOrFail($request->pic);

            $data = array(
                // 'base_document_id' => $request->document,
                // 'title' => $request->title,
                'type' => $request->type,
                'status' => 1,
                'purpose' => $request->purpose,
                'pic_id' => $request->pic,
                'position' => $pic->user_id,
                'created_by' => Auth::user()->id
            );

            $insert = RequestExisting::create($data);

            // document
            if ($request->document) {
                foreach ($request->document as $key => $req) {
                    $doc = [
                        'request_existing_id' => $insert->id,
                        'title' => $request->document[$key],
                    ];

                    RequestExistingDocument::create($doc);
                }
            }

            // History
            $dataHistory = array(
                'request_existing_id' => $insert->id,
                'step' => 1,
                'created_by' => Auth::user()->id,
                'step_name' => 'Request Existing Document'
            );

            RequestExistingHistory::create($dataHistory);

            addNotification($pic->user_id, 'tracking-existing.show', 'Request Existing Document', $insert->id);

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('request-existing.index');
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
            $id = Hashids::decode($id);

            $data = array(
                'tracking' => RequestExisting::findOrFail($id['0']),
                'document' => RequestExistingActivity::where('request_existing_id', $id['0'])->get(),
                'existing' => RequestExistingDocument::where('request_existing_id', $id['0'])->first(),
                'today' =>  Carbon::now()->format('Y-m-d'),
            );

            clickedNotification(Auth::user()->id, $id['0'], 'Legal upload request existing document');

            return view('user.request_existing.show')->with('data', $data);
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
        try {
            $id = Hashids::decode($id);

            $data = array(
                'tracking' => RequestExisting::findOrFail($id['0']),
                'pic' => Pic::all()
            );

            return view('user.request_existing.edit')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateRequest(Request $request, $id)
    {
        try {
            $id = Hashids::decode($id);

            $request_existing = RequestExisting::findOrFail($id['0']);
            $request_existing->title = $request->title;
            $request_existing->type = $request->type;
            $request_existing->purpose = $request->purpose;
            $request_existing->pic_id = $request->pic;
            $request_existing->update();

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('request-existing.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Hashids::decode($id);

            $request_existing = RequestExisting::findOrFail($id['0']);
            $pic = Pic::findOrFail($request_existing->pic_id);

            $request_existing->status = 5;
            $request_existing->position = $request_existing->created_by;
            $request_existing->update();

            // History
            $dataHistory = array(
                'request_existing_id' => $id['0'],
                'step' => 5,
                'created_by' => Auth::user()->id,
                'step_name' => 'Complete'
            );

            RequestExistingHistory::create($dataHistory);

            // Notification
            addNotification($pic->user_id, 'tracking-existing.show', 'Your document has approved', $id['0']);

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('request-existing.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try{
        $id = Hashids::decode($id);
        $doc = RequestExisting::findOrFail($id['0']);

        $pic = Pic::findOrFail($doc->pic_id);

        deleteNotification($pic->user_id, 'tracking-existing.show', 'Request Existing Document', $id['0']);

        // $notification = Notification::where('user_id', $pic->user_id)->where('url', 'tracking-existing.show')->where('feature', 'Request Existing Document')->where('id_feature', $id['0'])->get();

        $doc->is_deleted = true;
        $doc->update();

        Alert::success('Data Delete Successfully', 'Success Message');

        return redirect()->route('request-existing.index');
        // } catch (Exception $e) {
        //     $this->ErrorLog($e);
        // } 
    }

    public function download($id)
    {
        $id = Hashids::decode($id);
        $tracking = RequestExistingDocument::findOrFail($id['0']);
        $file = "upload/document/request_existing/" . $tracking->file;
        $filePath = public_path($file);
        $fileName = $tracking->file;

        return response()->download($filePath, $fileName);
    }

    public function feedback(Request $request)
    {
        try {
            $id = $request->id;

            $fileName = '-';
            if ($request->file) {
                // File
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'request_existing_document-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/document/request_existing'), $fileName);
            }

            $request_existing = RequestExisting::findOrFail($id);
            $pic = Pic::findOrFail($request_existing->pic_id);

            $request_existing->position = $pic->user_id;
            $request_existing->status = 4;
            $request_existing->update();

            // Acitivity
            $dataActivity = array(
                'request_existing_id' => $id,
                'file' => $fileName,
                'description' => $request->description,
                'created_by' => Auth::user()->id
            );

            RequestExistingActivity::create($dataActivity);

            // History
            $dataHistory = array(
                'request_existing_id' => $id,
                'step' => 4,
                'created_by' => Auth::user()->id,
                'step_name' => 'Feedback document'
            );

            RequestExistingHistory::create($dataHistory);

            // Notification
            addNotification($pic->user_id, 'tracking-existing.show', 'Your document getting feedback', $id);

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('request-existing.show', Hashids::encode($id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
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
}
