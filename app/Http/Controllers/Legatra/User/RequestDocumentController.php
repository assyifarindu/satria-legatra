<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\AppName\MstDept;
use App\Models\Table\Company;
use App\Models\Table\Document;
use App\Models\Table\DocumentFinal;
use App\Models\Table\DocumentHistory;
use App\Models\Table\DocumentHistoryAttachment;
use App\Models\Table\DocumentScope;
use App\Models\Table\Feedback;
use App\Models\Table\GenerateNumber;
use App\Models\Table\Negotiation;
use App\Models\Table\Notification;
use App\Models\Table\ParaPihak;
use App\Models\Table\Pic;
use App\Models\Table\PicDocument;
use App\Models\Table\RequestDocument;
use App\Models\Table\RequestDocumentActivity;
use App\Models\Table\Title;
use App\Models\Table\UploadDocument;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use ZipArchive;
use Illuminate\Support\Facades\Mail;
use App\Models\Department;

class RequestDocumentController extends Controller
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
    public function index()
    {
        try {
            $data = [
                'tracking' => RequestDocument::where('created_by', Auth::user()->id)->where('is_extend', false)->orderBy('created_at', 'desc')->get()
            ];

            return view('user.request_document.index')->with('data', $data);
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
            
            $company_id = $user->companyid;
            // dd($company_name);
            $company = Company::where('company_id', $company_id)->first();
            
            $data = array(
                'pic' => Pic::where('company_id',$company->id)->where('name','!=', 'DIAN LESTARI ASTUTY')->get(),
                // 'pic' => Pic::all(),
                // 'department' => getDepartment($company_name),
                'department' => Department::where('company_id', $company_id)->where('department_code_sap', '!=', 'null')->get(),
                'company_name' => $company->name,
            );


            return view('user.request_document.create')->with('data', $data);
        } catch (Exception $e) {
            // dd($e);
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
            $pic = Pic::findOrFail($request->email);
            sendEmailPic();


            $extend_automatically = false;

            if ($request->extend_automatically == 'on') {
                $extend_automatically = true;
            }

            $unlimited_duration = false;

            if ($request->unlimited_duration == 'on') {
                $unlimited_duration = true;
            }

            if ($request->type == 'Contract') {

                $data = array(
                    'title' => $request->title,
                    'type' => $request->type,
                    'para_pihak' => '-',
                    'file' => '-',
                    'scope' => $request->scope,
                    'email' => $pic->email,
                    'pic_id' => $request->email,
                    'status' => 0,
                    'is_extend_automatically' => $extend_automatically,
                    'is_unlimited_duration' => $unlimited_duration,
                    'created_by' => Auth::user()->id,
                    'note' => $request->note,
                );

               

                $insert = RequestDocument::create($data);

                // Acitivity
                $dataActivity = array(
                    'request_document_id' => $insert->id,
                    'step' => 0,
                    'created_by' => Auth::user()->id,
                    'step_name' => 'Request Document'
                );

                

                RequestDocumentActivity::create($dataActivity);

                // Para pihak
                for ($i = 0; $i < count($request->para_pihak); $i++) {
                    $data_para_pihak = array(
                        'request_document_id' => $insert->id,
                        'name' => $request->para_pihak[$i]
                    );
                    ParaPihak::create($data_para_pihak);
                }
            } else {
                $data = array(
                    'title' => $request->title,
                    'type' => $request->type,
                    'para_pihak' => '-',
                    'file' => '-',
                    'scope' => '-',
                    'email' => $pic->email,
                    'pic_id' => $request->email,
                    'status' => 0,
                    'is_extend_automatically' => $extend_automatically,
                    'is_unlimited_duration' => $unlimited_duration,
                    'created_by' => Auth::user()->id,
                    'note' => $request->note,
                );

                $insert = RequestDocument::create($data);

                // Acitivity
                $dataActivity = array(
                    'request_document_id' => $insert->id,
                    'step' => 0,
                    'created_by' => Auth::user()->id,
                    'step_name' => 'Request Document'
                );

                RequestDocumentActivity::create($dataActivity);
            }

            // Para pihak

            for ($i = 0; $i < count($request->attachment); $i++) {

                if ($request->attachment[$i] != '') {

                    $name = explode('.', request()->attachment[$i]->getClientOriginalName());

                    $fileName = $name[0] . '-' . time() . '.' . request()->attachment[$i]->getClientOriginalExtension();
                    request()->attachment[$i]->move(public_path('upload/request_document'), $fileName);

                    $data_upload = array(
                        'request_document_id' => $insert->id,
                        'file' => $fileName
                    );
                    UploadDocument::create($data_upload);
                }
            }

            // Document Scope
            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'request_document_id' => $insert->id,
                    'department_code' => $scope[0],
                    'department_name' => $scope[1]
                );

                DocumentScope::create($data_scope);
            }

            $check = DocumentScope::where('request_document_id', $insert->id)->where('department_code', Auth::user()->dept)->count();

            if ($check < 1) {
                $data_scope_single = array(
                    'request_document_id' => $insert->id,
                    'department_code' => Auth::user()->dept,
                    'department_name' => Auth::user()->department
                );

                DocumentScope::create($data_scope_single);
            }

            // mail to requester and atassan
            $detail_email = array(
                'title' => $request->title,
            );

            Mail::to(Auth::user()->email_sf)->send(new \App\Mail\RequestDocument\EmailToRequesterRequest($detail_email));

            // $atasan = $this->get_atasan(Auth::user()->personal_number);

            $user = User:: where('id', Auth::user()->id)->first();
            $dept = Department::where('id', $user->dept)->first();
            $atasan = $dept->depthead_nrp;

            if ($atasan != null) {
                $nrp_atasan = $atasan;
                $data_atasan =  User::where('email', $nrp_atasan)->orWhere('personal_number', $nrp_atasan)->first();

                Mail::to($data_atasan->email_sf)->send(new \App\Mail\RequestDocument\EmailToRequesterRequest($detail_email));
            }


            if ($request->type == 'Contract') {
                addNotification($pic->user_id, 'tracking.show', 'Request Contract/Letter', $insert->id);
            } elseif ($request->type == 'License') {
                addNotification($pic->user_id, 'tracking-license.show', 'Request License', $insert->id);
            } else {
                addNotification($pic->user_id, 'tracking-haki.show', 'Request HAKI', $insert->id);
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('request-document.index');
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
                'tracking' => RequestDocument::findOrFail($id['0']),
                'document' => UploadDocument::where('request_document_id', $id['0'])->get()
            );

            clickedNotification(Auth::user()->id, $id['0'], 'Legal has created draft');
            clickedNotification(Auth::user()->id, $id['0'], 'Legal has updated draft');
            clickedNotification(Auth::user()->id, $id['0'], 'Your Request continue to send rekanan');
            clickedNotification(Auth::user()->id, $id['0'], 'Your Request continue to negotiation');
            clickedNotification(Auth::user()->id, $id['0'], 'Your Request has completed');

            return view('user.request_document.show')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function showDocument($id)
    {
        try {
            $id = Hashids::decode($id);

            $data = array(
                'tracking' => RequestDocument::findOrFail($id['0']),
                'document' => UploadDocument::where('request_document_id', $id['0'])->get()
            );

            clickedNotification(Auth::user()->id, $id['0'], 'Legal has created draft');
            clickedNotification(Auth::user()->id, $id['0'], 'Request getting feedback');
            clickedNotification(Auth::user()->id, $id['0'], 'Request is continue to registration');
            clickedNotification(Auth::user()->id, $id['0'], 'Request is continue to complete');
            clickedNotification(Auth::user()->id, $id['0'], 'Request has done');


            return view('user.request_document.show-license')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function showHaki($id)
    {
        try {
            $id = Hashids::decode($id);
            $data = array(
                'tracking' => RequestDocument::findOrFail($id['0']),
                'document' => UploadDocument::where('request_document_id', $id['0'])->get()
            );

            clickedNotification(Auth::user()->id, $id['0'], 'Legal has created draft');
            clickedNotification(Auth::user()->id, $id['0'], 'Request getting feedback');
            clickedNotification(Auth::user()->id, $id['0'], 'Request is continue to registration');
            clickedNotification(Auth::user()->id, $id['0'], 'Request is continue to complete');
            clickedNotification(Auth::user()->id, $id['0'], 'Request has done');

            return view('user.request_document.show-haki')->with('data', $data);
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
            $user = User::where('id', Auth::user()->id)->first();
            // $company_name = $user->company_name;
            // $company = Company::where('name', $company_name)->first();
            
            $company_id = $user->companyid;
            // dd($company_name);
            $company = Company::where('company_id', $company_id)->first();


            $data = array(
                'tracking' => RequestDocument::findOrFail($id['0']),
                'first_pihak' => ParaPihak::where('request_document_id', $id['0'])->orderBy('id', 'asc')->first(),
                'all_pihak' => ParaPihak::where('request_document_id', $id['0'])->orderBy('id', 'asc')->get(),
                'pic' => Pic::all(),
                'document' => UploadDocument::where('request_document_id', $id['0'])->get(),
                // 'department' => getDepartment($company_name),
                'department' => Department::where('company_id', $company_id)->get(),

            );
            return view('user.request_document.edit')->with('data', $data);
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
    public function update(Request $request, $id)
    {
        try {
            $tracking = RequestDocument::findOrFail($id);
            $tracking->title = $request->title;
            $tracking->type = $request->type;

            $extend_automatically = false;

            if ($request->extend_automatically == 'on') {
                $extend_automatically = true;
            }

            $unlimited_duration = false;

            if ($request->unlimited_duration == 'on') {
                $unlimited_duration = true;
            }


            if (!empty($request->file)) {
                // delete old picture
                $doc = public_path('upload/request_document/') . $tracking->file;
                unlink($doc);

                // upload new picture
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'RequestDocument-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/request_document'), $fileName);

                $tracking->file = $fileName;
            }

            $pic = Pic::findOrFail($request->email);

            if ($request->type == 'Contract') {
                ParaPihak::where('request_document_id', $id)->delete();
                for ($i = 0; $i < count($request->para_pihak); $i++) {
                    $data_pihak = array(
                        'request_document_id' => $id,
                        'name' => $request->para_pihak[$i],
                    );
                    if ($request->para_pihak[$i] != null) {
                        ParaPihak::create($data_pihak);
                    }
                }
                $tracking->scope = $request->scope;
                $tracking->email = $pic->email;
                $tracking->pic_id = $request->email;
                $tracking->is_extend_automatically = $extend_automatically;
                $tracking->is_unlimited_duration = $unlimited_duration;
                $tracking->note = $request->note;
            } else {
                $tracking->email = $pic->email;
                $tracking->pic_id = $request->email;
                $tracking->is_extend_automatically = $extend_automatically;
                $tracking->is_unlimited_duration = $unlimited_duration;
                $tracking->note = $request->note;
            }

            $tracking->update();

            // Document Scope
            $document_scope = DocumentScope::where('request_document_id', $tracking->id)->get();

            foreach ($document_scope as $key) {
                $get_dept = DocumentScope::findOrFail($key->id);
                $get_dept->delete();
            }

            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'request_document_id' => $tracking->id,
                    'department_code' => $scope[0],
                    'department_name' => $scope[1]
                );

                DocumentScope::create($data_scope);
            }

            $check = DocumentScope::where('request_document_id', $tracking->id)->where('department_code', Auth::user()->dept)->count();

            if ($check < 1) {
                $data_scope_single = array(
                    'request_document_id' => $tracking->id,
                    'department_code' => Auth::user()->dept,
                    'department_name' => Auth::user()->department
                );

                DocumentScope::create($data_scope_single);
            }

            Alert::success('Data Update Successfully', 'Success Message');

            return redirect()->route('request-document.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function addUpload(Request $request)
    {
        try {
            if (!empty($request->file)) {
                // upload new picture
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = request()->file->getClientOriginalName() . '-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/request_document'), $fileName);

                $data = array(
                    'request_document_id' => $request->id,
                    'file' => $fileName
                );

                UploadDocument::create($data);
            }

            Alert::success('Data Created Successfully', 'Success Message');

            return redirect()->route('request-document.edit', Hashids::encode($request->id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function destroyUpload($id)
    {
        try {
            $id = Hashids::decode($id);
            $doc = UploadDocument::findOrFail($id['0']);
            $req_id = $doc->request_document_id;

            if (!empty($doc->file)) {
                // delete old file
                $docfile = public_path('upload/request_document/') . $doc->file;
                unlink($docfile);
            }

            $doc->delete();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('request-document.edit', Hashids::encode($req_id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
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
        //
    }

    public function feedbackDraft(Request $request)
    {
        try {
            // File
            $fileName = null;
            if ($request->file != null) {
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'Feedback-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/document/feedback'), $fileName);
            }

            $data = array(
                'request_document_id' => $request->id,
                'document_id' => $request->id_document,
                'feedback' => $request->feedback,
                'file' => $fileName,
                'created_by' => Auth::user()->id
            );

            Feedback::create($data);

            $request_document = RequestDocument::findOrFail($request->id);
            sendEmailFeedbacktoPic($request_document);

            if ($request_document->type == 'License') {
                // Notification
                addNotification($request_document->Pic->user_id, 'tracking-license.show', 'Request getting feedback', $request_document->id);
                Alert::success('Data Update Successfully', 'Success Message');
                return redirect()->route('request-document.show-license', Hashids::encode($request->id));
            } elseif ($request_document->type == 'HAKI') {
                // Notification
                addNotification($request_document->Pic->user_id, 'tracking-haki.show', 'Request getting feedback', $request_document->id);
                Alert::success('Data Update Successfully', 'Success Message');
                return redirect()->route('request-document.show-haki', Hashids::encode($request->id));
            } else {
                return redirect()->back()->with('error', 'Error Request, Exception Error ');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function feedbackDownload($id)
    {
        $feedback = Feedback::findOrFail($id);
        $file = "upload/document/feedback/" . $feedback->file;
        $filePath = public_path($file);
        $fileName = $feedback->file;

        return response()->download($filePath, $fileName);
    }

    public function downloadDocument($id)
    {
        $document = Document::findOrFail($id);
        $file = "upload/document/contract/" . $document->file;
        $filePath = public_path($file);
        $fileName = $document->file;

        return response()->download($filePath, $fileName);
    }

    public function downloadDocumentHistory($id)
    {
        $document = DocumentHistory::findOrFail($id);
        $file = "upload/document/contract/" . $document->file;
        $filePath = public_path($file);
        $fileName = $document->file;

        return response()->download($filePath, $fileName);
    }

    public function download($id)
    {
        $tracking = RequestDocument::findOrFail($id);
        $file = "upload/request_document/" . $tracking->file;
        $filePath = public_path($file);
        $fileName = $tracking->file;

        return response()->download($filePath, $fileName);
    }

    public function downloadUpload($id)
    {
        $tracking = UploadDocument::findOrFail($id);
        $file = "upload/request_document/" . $tracking->file;
        $filePath = public_path($file);
        $fileName = $tracking->file;

        return response()->download($filePath, $fileName);
    }

    public function approveDraft($id)
    {
        try {
            $id = Hashids::decode($id);
            $request_document = RequestDocument::findOrFail($id['0']);
            $request_document->status = 5;
            $request_document->update();

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $id['0'],
                'step' => 5,
                'created_by' => Auth::user()->id,
                'step_name' => 'Approve Draft'
            );

            RequestDocumentActivity::create($dataActivity);
            sendEmailFeedbacktoPic($request_document);

            // Notification
            addNotification($request_document->Pic->user_id, 'tracking.show', 'Draft Approved', $request_document->id);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-document.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function feedbackDraftContract(Request $request)
    {
        try {

            // File
            $fileName = null;
            if ($request->file != null) {
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'Feedback-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/document/feedback'), $fileName);
            }

            $data = array(
                'request_document_id' => $request->id,
                'document_id' => $request->id_document,
                'feedback' => $request->feedback,
                'file' => $fileName,
                'created_by' => Auth::user()->id
            );

            Feedback::create($data);

            $request_document = RequestDocument::findOrFail($request->id);
            $request_document->status = 3;
            $request_document->update();

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $request->id,
                'step' => 3,
                'created_by' => Auth::user()->id,
                'step_name' => 'Feedback Draft'
            );

            RequestDocumentActivity::create($dataActivity);

            // update document
            $document = Document::findOrFail($request->id_document);
            $document->is_negotiation = true;
            $document->update();

            sendEmailFeedbacktoPic($request_document);

            // Notification
            addNotification($request_document->Pic->user_id, 'tracking.show', 'Draft getting feedback', $request_document->id);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-document.show', Hashids::encode($request->id));
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

            $document = RequestDocumentActivity::where('request_document_id', $doc_id)->orderBy('step', 'asc')->get();
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

    public function negotiation(Request $request)
    {
        try {

            // File
            $fileName = null;
            if ($request->file != null) {
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'Negotiation-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/document/negotiation'), $fileName);
            }

            $data = array(
                'request_document_id' => $request->id,
                'document_id' => $request->id_document,
                'note' => $request->note,
                'file' => $fileName,
                'created_by' => Auth::user()->id
            );

            Negotiation::create($data);

            $request_document = RequestDocument::findOrFail($request->id);
            $request_document->status = 6;
            $request_document->update();

            $document = Document::findOrFail($request->id_document);
            $document->is_negotiation = true;
            $document->update();

            sendEmailNegotiation($request_document);

            // Notification
            addNotification($request_document->Pic->user_id, 'tracking.show', 'Request continue to negotiation', $request_document->id);

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $request->id,
                'step' => 6,
                'created_by' => Auth::user()->id,
                'step_name' => 'Negotiation'
            );

            RequestDocumentActivity::create($dataActivity);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-document.show', Hashids::encode($request->id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function negotiationApprove($id)
    {
        try {
            $id = Hashids::decode($id);

            $document = Document::findOrFail($id['0']);
            $document->is_negotiation = false;
            $document->is_final = true;

            // check penomoran
            $year = date("Y");
            $representative_year = substr($year, -2);
            $get_month = date("m");
            $month = getRomawiMonth($get_month);
            $start_date = $year . "-01-01";
            $end_date = $year . "-12-31";

            $get_seq_temp = Document::whereBetween('created_at', [$start_date, $end_date])->where('category', 1)->where('status', 1)->count();
            $get_sequence = $get_seq_temp + 1;

            if ($get_sequence < 100) {
                if ($get_sequence == 0) {
                    $sequence = '001';
                } else {
                    if ($get_sequence < 10) {
                        $sequence = '00' . $get_sequence;
                    } else {
                        $sequence = '0' . $get_sequence;
                    }
                }
            } else {
                $sequence = $get_sequence;
            }

            $company = Company::where('name', $document->company)->first();
            $title = Title::findOrFail($document->title_id);

            if ($document->document_type == 'Surat') {

                if ($document->letter_type == 'Surat Keluar') {
                    $jenis_surat = 'Let';

                    $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;
                }
                if ($document->letter_type == 'Surat Kuasa') {
                    $jenis_surat = 'SK';

                    $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;
                }
            } else {
                $jenis_surat = 'Agg';

                $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $sequence . '/' . $month . '/' . $representative_year;
            }

            $check = GenerateNumber::where('document_number', $contract_number)->first();

            if ($check) {
                $new_sequence = $sequence + 1;

                if ($new_sequence < 100) {
                    if ($new_sequence == 0) {
                        $seq = '001';
                    } else {
                        if ($new_sequence < 10) {
                            $seq = '00' . $new_sequence;
                        } else {
                            $seq = '0' . $new_sequence;
                        }
                    }
                } else {
                    $seq = $new_sequence;
                }

                if ($document->document_type == 'Surat') {

                    if ($document->letter_type == 'Surat Keluar') {
                        $jenis_surat = 'Let';

                        $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $seq . '/' . $month . '/' . $representative_year;
                    }
                    if ($document->letter_type == 'Surat Kuasa') {
                        $jenis_surat = 'SK';

                        $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $seq . '/' . $month . '/' . $representative_year;
                    }
                } else {
                    $jenis_surat = 'Agg';

                    $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $seq . '/' . $month . '/' . $representative_year;
                }
            }


            $document->contract_number = $contract_number;
            $document->status = 1;
            $document->update();

            addNotification($document->RequestDocument->Pic->user_id, 'tracking.show', 'Draft Approved', $document->request_document_id);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-document.show', Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function negotiationDownload($id)
    {
        $feedback = Negotiation::findOrFail($id);
        $file = "upload/document/negotiation/" . $feedback->file;
        $filePath = public_path($file);
        $fileName = $feedback->file;

        return response()->download($filePath, $fileName);
    }

    public function feedbackApprove($id)
    {
        try {
            $id = Hashids::decode($id);

            $document = Document::findOrFail($id['0']);
            $document->is_negotiation = false;
            $document->update();

            $request_document = RequestDocument::findOrFail($document->request_document_id);
            $request_document->status = 4;
            $request_document->update();
            sendEmailFeedbacktoPic($request_document);

            addNotification($request_document->Pic->user_id, 'tracking.show', 'Draft Approved', $document->request_document_id);

            Alert::success('Data Update Successfully', 'Success Message');

            if ($request_document->is_extend == 1) {
                return redirect()->route('request-extend.show', Hashids::encode($document->request_document_id));
            } else {
                return redirect()->route('request-document.show', Hashids::encode($document->request_document_id));
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }


    public function downloadAttachment($id)
    {
        $tracking = DocumentHistoryAttachment::findOrFail($id);
        $file = "upload/drafting/" . $tracking->file;
        $filePath = public_path($file);
        $fileName = $tracking->file;

        return response()->download($filePath, $fileName);
    }

    public function allAttachment($id)
    {
        try {
            $id = Hashids::decode($id);

            $attachment = UploadDocument::where('request_document_id', $id['0'])->get();
            $request = RequestDocument::findOrFail($id['0']);

            $zip = new ZipArchive;
            $zipFileName = "upload/request_document/" . 'Attachment-' . $request->title . '.zip';

            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($attachment as $key => $value) {
                    $file = "upload/request_document/" . $value->file;
                    $filePath = public_path($file);

                    $filesToZip[] = $filePath;
                }

                foreach ($filesToZip as $file) {
                    $zip->addFile($file, basename($file));
                }

                $zip->close();

                return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
            }
        } catch (Exception $e) {
            // dd($e);
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function uploadFinal(Request $request)
    {
        try {
            $request_document = RequestDocument::findOrFail($request->id_request_document);

            if ($request->file) {
                $request->validate([
                    'file' => 'required',
                ]);

                $name = explode('.', request()->file->getClientOriginalName());

                $fileName = $name[0] . '-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/document_final'), $fileName);

                // Acitivity
                $data = array(
                    'document_id' => $request->id_document,
                    'file' => $fileName,
                    'created_by' => Auth::user()->id
                );

                DocumentFinal::create($data);
                sendEmailFeedbacktoPic($request_document);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function downloadFinal($id)
    {
        try {
            $feedback = DocumentFinal::findOrFail($id);
            $file = "upload/document_final/" . $feedback->file;
            $filePath = public_path($file);
            $fileName = $feedback->file;

            return response()->download($filePath, $fileName);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function cancel(Request $request)
    {
        try {
            $request_document = RequestDocument::findOrFail($request->id_cancel);
            $request_document->is_cancel = true;
            $request_document->update();

            $pic = Pic::findOrFail($request_document->pic_id);

            if ($request_document->type == 'Contract') {
                $notif = Notification::where('user_id', $pic->user_id)->where('url', 'tracking.show')->where('feature', 'Request Contract/Letter')->where('id_feature', $request->id_cancel)->delete();
            } elseif ($request_document->type == 'License') {
                $notif = Notification::where('user_id', $pic->user_id)->where('url', 'tracking-license.show')->where('feature', 'Request License')->where('id_feature', $request->id_cancel)->delete();
            } else {
                $notif = Notification::where('user_id', $pic->user_id)->where('url', 'tracking-haki.show')->where('feature', 'Request HAKI')->where('id_feature', $request->id_cancel)->delete();
            }

            Alert::success('Request Has Cancelled', 'Success Message');
            return redirect()->route('request-document.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function getPic()
    {
        try {
            $document = Document::get();

            foreach ($document as $key => $value) {

                $data = array(
                    'document_id' => $value->id,
                    'user_id' => $value->pic
                );

                PicDocument::create($data);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
