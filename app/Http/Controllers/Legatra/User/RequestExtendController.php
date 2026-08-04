<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\BaseDocument;
use App\Models\Table\Company;
use App\Models\Table\Document;
use App\Models\Table\DocumentHistory;
use App\Models\Table\DocumentScope;
use App\Models\Table\EmailAlert;
use App\Models\Table\Feedback;
use App\Models\Table\Notification;
use App\Models\Table\ParaPihak;
use App\Models\Table\Pic;
use App\Models\Table\RequestDocument;
use App\Models\Table\RequestDocumentActivity;
use App\Models\Table\Title;
use App\Models\Table\UploadDocument;
use App\Models\View\VwFiling;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\User;
use App\Models\Department;

class RequestExtendController extends Controller
{
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
                'tracking' => RequestDocument::where('created_by', Auth::user()->id)->where('is_extend', true)->orderBy('created_at', 'desc')->get()
            ];

            return view('user.request_extend.index')->with('data', $data);
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
            $company = Company::where('company_id', $company_id)->first();


            $data = array(
                'pic' => Pic::where('company_id',$company->id)->get(),
                // 'department' => getDepartment($company_name)
                'department' => Department::where('company_id', $company_id)->get(),
            );
            return view('user.request_extend.create')->with('data', $data);
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

            if ($request->type == 1) {
                $tipe = 'Contract';
                $check_request = RequestDocument::where('base_document_id', $request->title)->where('is_extend', true)->where('is_cancel', false)->where('status', '!=', 7)->count();
            } elseif ($request->type == 2) {
                $tipe = 'License';
                $check_request = RequestDocument::where('base_document_id', $request->title)->where('is_extend', true)->where('is_cancel', false)->where('status', '!=', 4)->count();
            } else {
                $tipe = 'HAKI';
                $check_request = RequestDocument::where('base_document_id', $request->title)->where('is_extend', true)->where('is_cancel', false)->where('status', '!=', 4)->count();
            }

            // if ($check_request > 0) {
            //     Alert::warning('Request tidak dapat disimpan. Terdapat request lain yang sedang berjalan dengan dokumen yang sama.', 'Warning Message');
            //     return redirect()->route('request-extend.index');
            // }


            $extend_automatically = false;

            if ($request->extend_automatically == 'on') {
                $extend_automatically = true;
            }

            $unlimited_duration = false;

            if ($request->unlimited_duration == 'on') {
                $unlimited_duration = true;
            }

            $base_document = BaseDocument::findOrFail($request->title);
            $base_document->is_proceed = true;
            $base_document->update();

            if ($tipe == 'Contract') {
                $data = array(
                    'title' => $base_document->description,
                    'type' => $tipe,
                    'para_pihak' => '-',
                    'file' => '-',
                    'scope' => $request->scope,
                    'email' => $pic->email,
                    'pic_id' => $request->email,
                    'status' => 0,
                    'is_extend' => true,
                    'base_document_id' => $request->title,
                    'created_by' => Auth::user()->id,
                    'is_extend_automatically' => $extend_automatically,
                    'is_unlimited_duration' => $unlimited_duration,
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
                    'title' => $base_document->description,
                    'type' => $tipe,
                    'para_pihak' => '-',
                    'file' => '-',
                    'scope' => '-',
                    'email' => $pic->email,
                    'pic_id' => $request->email,
                    'status' => 0,
                    'is_extend' => true,
                    'base_document_id' => $request->title,
                    'created_by' => Auth::user()->id,
                    'is_extend_automatically' => $extend_automatically,
                    'is_unlimited_duration' => $unlimited_duration,
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

            // Attachment
            if ($request->attachment != null) {
                # code...
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

            if ($tipe == 'Contract') {
                addNotification($pic->user_id, 'tracking.show', 'Request Contract/Letter', $insert->id);
            } elseif ($tipe == 'License') {
                addNotification($pic->user_id, 'tracking-license.show', 'Request License', $insert->id);
            } else {
                addNotification($pic->user_id, 'tracking-haki.show', 'Request HAKI', $insert->id);
            }

            if ($request->email_alert != 0) {
                $email = EmailAlert::findOrFail($request->email_alert);
                $email_alert = EmailAlert::where('base_document_id', $email->base_document_id)->where('document_id', $email->document_id)->get();
                foreach ($email_alert as $ea) {
                    $temp = EmailAlert::findOrFail($ea->id);
                    $temp->is_action = true;
                    $temp->update();
                }
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('request-extend.index');
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

            return view('user.request_extend.show')->with('data', $data);
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


            return view('user.request_extend.show-license')->with('data', $data);
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

            return view('user.request_extend.show-haki')->with('data', $data);
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
            $company_id = $user->companyid;
            $company_name = $user->company_name;
            $company = Company::where('name', $company_name)->first();


            $request = RequestDocument::findOrFail($id['0']);

            if ($request->type == 'Contract') {
                $type = 1;
            } elseif ($request->type == 'License') {
                $type = 2;
            } else {
                $type = 3;
            }


            $data = array(
                'tracking' => RequestDocument::findOrFail($id['0']),
                'first_pihak' => ParaPihak::where('request_document_id', $id['0'])->orderBy('id', 'asc')->first(),
                'all_pihak' => ParaPihak::where('request_document_id', $id['0'])->orderBy('id', 'asc')->get(),
                'pic' => Pic::all(),
                'document' => UploadDocument::where('request_document_id', $id['0'])->get(),
                'base_document' => VwFiling::where('category', $type)->where('status', 1)->where('department_code', Auth::user()->dept)->get(),
                // 'department' => getDepartment($company_name),
                // 'department' => Department::where('company_name', $company_name)->get(),
                'department' => Department::where('company_id', $company_id)->get(),
            );
            return view('user.request_extend.edit')->with('data', $data);
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

            $base_document = BaseDocument::findOrFail($request->title);

            if (!empty($request->file)) {
                // delete old picture
                $doc = public_path('upload/request_document/') . $tracking->file;
                unlink($doc);

                // upload new picture
                $request->validate([
                    'file' => 'required',
                ]);

                $name = explode('.', request()->file->getClientOriginalName());

                $fileName =  $name[0] . '-' . time() . '.' . request()->file->getClientOriginalExtension();
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

            $tracking->title = $base_document->description;
            $tracking->base_document_id = $request->title;
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

            return redirect()->route('request-extend.index');
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

                $name = explode('.', request()->file->getClientOriginalName());

                $fileName = $name[0] . '-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/request_document'), $fileName);

                $data = array(
                    'request_document_id' => $request->id,
                    'file' => $fileName
                );

                UploadDocument::create($data);
            }

            Alert::success('Data Created Successfully', 'Success Message');

            return redirect()->route('request-extend.edit', Hashids::encode($request->id));
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

            return redirect()->route('request-extend.edit', Hashids::encode($req_id));
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

            if ($request_document->type == 'License') {
                // Notification
                addNotification($request_document->Pic->user_id, 'tracking-license.show', 'Request getting feedback', $request_document->id);
                Alert::success('Data Update Successfully', 'Success Message');
                return redirect()->route('request-extend.show-license', Hashids::encode($request->id));
            } elseif ($request_document->type == 'HAKI') {
                // Notification
                addNotification($request_document->Pic->user_id, 'tracking-haki.show', 'Request getting feedback', $request_document->id);
                Alert::success('Data Update Successfully', 'Success Message');
                return redirect()->route('request-extend.show-haki', Hashids::encode($request->id));
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

            // Notification
            addNotification($request_document->Pic->user_id, 'tracking.show', 'Draft Approved', $request_document->id);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-extend.show', Hashids::encode($id['0']));
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

            // Notification
            addNotification($request_document->Pic->user_id, 'tracking.show', 'Draft getting feedback', $request_document->id);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-extend.show', Hashids::encode($request->id));
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


    public function getItem(Request $request)
    {
        $id = $request->category;

        return DB::connection('legatra')->table('vw_filing')->selectRaw('CONCAT(contract_number, " - ", description) AS con_desc, id')->where('category', $id)->where('status', 1)->where('is_unlimited_duration', false)->where('department_code', Auth::user()->dept)->get()->pluck('con_desc', 'id');
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

            $document->contract_number = $contract_number;
            $document->status = 1;
            $document->update();

            addNotification($document->RequestDocument->Pic->user_id, 'tracking.show', 'Draft Approved', $document->request_document_id);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('request-extend.show', Hashids::encode($document->request_document_id));
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
            return redirect()->route('request-extend.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
