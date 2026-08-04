<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert as TableAlert;
use App\Models\Table\BaseDocument;
use App\Models\Table\BaseDocumentFinal;
use App\Models\Table\Company;
use App\Models\Table\Document;
use App\Models\Table\DocumentFinalAttachment;
use App\Models\Table\DocumentHistory;
use App\Models\Table\DocumentHistoryAttachment;
use App\Models\Table\DocumentScope;
use App\Models\Table\Duty;
use App\Models\Table\Feedback;
use App\Models\Table\HakiType;
use App\Models\Table\PicDocument;
use App\Models\Table\RequestDocument;
use App\Models\Table\RequestDocumentActivity;
use App\Models\Table\Title;
use App\Models\Table\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\ExportHakiRequest;
use Maatwebsite\Excel\Facades\Excel;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\Department;

class HakiTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('tracking-haki') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('tracking-haki')->r == 1) {
                $id = Auth::user()->id;
                $user = User::findOrFail($id);
                $company = $user->companyid;

                $filter = '';

                if (isset($request->filter)) {
                    if ($request->filter == 'progress') {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'HAKI')->where('status', '!=', 4)->where('is_cancel', false)->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    } elseif ($request->filter == 'filing') {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'HAKI')->where('status', 4)->where('is_cancel', false)->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    } elseif ($request->filter == 'cancel') {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'HAKI')->where('is_cancel', true)->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    } else {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'HAKI')->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    }

                    $filter = $request->filter;
                } else {
                    // dd($company);
                    // $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                    // ->where('type', 'HAKI')->where('satria.users.company_name', $company)->orderBy('request_documents.created_at', 'desc')->get();
                    // $filter = 'all';

                    $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                    ->where('type', 'HAKI')->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                    ->select('request_documents.*')->get();
                    $filter = 'all';

                    // dd($tracking);
                }

                // dd($tracking);

                

                $data = [
                    'tracking' => $tracking,
                    'filter' => $filter
                ];

                return view('main.tracking_haki.index')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            // dd($e);
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
        //
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
            if ($this->PermissionActionMenu('tracking-haki')->r == 1) {
                $id = Hashids::decode($id);

                $data = array(
                    'tracking' => RequestDocument::findOrFail($id['0']),
                    'document' => UploadDocument::where('request_document_id', $id['0'])->get()
                );

                clickedNotification(Auth::user()->id, $id['0'], 'Request HAKI');
                clickedNotification(Auth::user()->id, $id['0'], 'Request getting feedback');

                return view('main.tracking_haki.show')->with('data', $data);
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

    public function legalDrafting($id)
    {
        try {

            $id = Hashids::decode($id);
            $request = RequestDocument::findOrFail($id['0']);
            $id_user = Auth::user()->id;
            $user = User::findOrFail($id_user);
            $company_name = $user->company_name;


            if ($request->is_extend == true) {
                $data = array(
                    'tracking' => RequestDocument::findOrFail($id['0']),
                    'pic' => User::whereNull('role_id')->where('company_name', $company_name)->get(),
                    'alert' => TableAlert::get(),
                    'company' => Company::where('name', $company_name)->first(),
                    'title' => Title::where('is_delete', 0)->get(),
                    'type' => HakiType::get(),
                    'duty' => Duty::get(),
                    'request_id' => $id['0'],
                    'base' => BaseDocument::findOrFail($request->base_document_id),
                    // 'department' => getDepartment($company_name)
                    'department' => Department::where('company_name', $company_name)->get(),
                );
            } else {
                $data = array(
                    'tracking' => RequestDocument::findOrFail($id['0']),
                    'pic' => User::whereNull('role_id')->where('company_name', $company_name)->get(),
                    'alert' => TableAlert::get(),
                    'company' => Company::where('name', $company_name)->first(),
                    'title' => Title::where('is_delete', 0)->get(),
                    'type' => HakiType::get(),
                    'duty' => Duty::get(),
                    'request_id' => $id['0'],
                    // 'department' => getDepartment($company_name)
                    'department' => Department::where('company_name', $company_name)->get(),
                );
            }


            return view('main.tracking_haki.create-draft')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function storeLegalDrafting(Request $request)
    {
        try {
            $id = $request->id;

            // category check
            $request_document = RequestDocument::findOrFail($id);
            $request_document->status = 1;
            $request_document->update();

            if ($request_document->type == 'Contract') {
                $category = 1;
            } elseif ($request_document->type == 'License') {
                $category = 2;
            } else {
                $category = 3;
            }

            if ($request->unlimited_duration == 'on') {
                $effective_date = $request->contract_date;
            } else {

                // Get date 
                $duration_date = $request->duration;
                $exp_date = explode(' to ', $duration_date);
                $effective_date = $exp_date[0];
                $expired_date = $exp_date[1];

                // get difference
                $duration_days = Carbon::parse($effective_date)->diffInDays(Carbon::parse($expired_date));
            }

            // unlimited duration
            $unlimited_duration = $request->unlimited_duration == 'on' ? 1 : 0;
            $extend_automatically = $request->extend_automatically == 'on' ? 1 : 0;

            // Company check
            $company = Company::findOrFail($request->company);

            // Store data
            $data = array(
                'description' => $request->document_title,
                'priority' => $request->priority,
                'contract_number' => '-',
                'category' => $category,
                'company_id' => $request->company,
                'company' => $company->name,
                'serial_number' => 0,
                'duration' => 0,
                'note' => $request->note,
                'file' => '-',
                'created_by' => Auth::user()->id,
                'is_extend' => 0,
                'status' => 0,
                'request_document_id' => $id,
                'revision' => 0,
                'title_id' => $request->title,
                'haki_type_id' => $request->type,
                'duty_id' => $request->duty,
                'launch_by' => getUserName($request_document->created_by)->department,
                'request_by' => $request_document->created_by,

                'alert_days' => $unlimited_duration == 1 ? NULL : $request->alert,
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'end_contract_date' => $unlimited_duration == 1 ? NULL : $expired_date,
                'is_extend_automatically' => $extend_automatically,
                'is_unlimited_duration' => $unlimited_duration,
                'duration_days' => $unlimited_duration == 1 ? NULL : $duration_days
            );

            $document = Document::create($data);

            $dataHistory = array(
                'description' => $request->document_title,
                'priority' => $request->priority,
                'contract_number' => '-',
                'category' => $category,
                'company' => $company->name,
                'contract_date' => $request->contract_date,
                'deal_date' => $request->contract_date,
                'duration' => 0,
                'note' => $request->note,
                'file' => '-',
                'document_id' => $document->id,
                'serial_number' => 0,
                'created_by' => Auth::user()->id,
                'status' => 1,
                'revision' => 0,
                'title_id' => $request->title,
                // 'document_type' => $document_type,
                // 'letter_type' => $letter_type,
                // 'letter_purpose' => $letter_purpose,
                'haki_type_id' => $request->type,
                'duty_id' => $request->duty,
                'launch_by' => getUserName($request_document->created_by)->department
            );

            $documentHistory = DocumentHistory::create($dataHistory);

            if (count($request->attachment) > 0) {
                for ($i = 0; $i < count($request->attachment); $i++) {

                    if ($request->attachment[$i] != '') {

                        $name = explode('.', request()->attachment[$i]->getClientOriginalName());

                        $fileName = $name[0] . '-' . time() . '.' . request()->attachment[$i]->getClientOriginalExtension();
                        request()->attachment[$i]->move(public_path('upload/drafting'), $fileName);

                        $data_upload = array(
                            'document_history_id' => $documentHistory->id,
                            'file' => $fileName
                        );
                        DocumentHistoryAttachment::create($data_upload);
                    }
                }
            }

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $id,
                'step' => 1,
                'created_by' => Auth::user()->id,
                'step_name' => 'Legal Drafting'
            );

            RequestDocumentActivity::create($dataActivity);

            // PIC
            $pic_document = PicDocument::where('document_id', $document->id)->get();
            foreach ($pic_document as $pd) {
                $get_pic = PicDocument::findOrFail($pd->id);
                $get_pic->delete();
            }

            foreach ($request->pic as $item_pic) {
                $data_pic = array(
                    'document_id' => $document->id,
                    'user_id' => $item_pic
                );

                PicDocument::create($data_pic);
            }
            // END PIC

            // Document Scope
            $document_scope = DocumentScope::where('request_document_id', $document->request_document_id)->get();

            foreach ($document_scope as $key) {
                $get_dept = DocumentScope::findOrFail($key->id);
                $get_dept->delete();
            }

            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'request_document_id' => $document->request_document_id,
                    'document_id' => $document->id,
                    'department_code' => $scope[0],
                    'department_name' => $scope[1]
                );

                DocumentScope::create($data_scope);
            }

            $requester = User::findOrFail($request_document->created_by);
            $check = DocumentScope::where('request_document_id', $document->request_document_id)->where('department_code', $requester->dept)->count();

            if ($check < 1) {
                $data_scope_single = array(
                    'request_document_id' => $document->request_document_id,
                    'document_id' => $document->id,
                    'department_code' => $requester->dept,
                    'department_name' => $requester->department
                );

                DocumentScope::create($data_scope_single);
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking-license.show', Hashids::encode($id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function editLegalDrafting($id)
    {
        try {
            $id = Hashids::decode($id);
            $id_user = Auth::user()->id;
            $user = User::findOrFail($id_user);
            $company_name = $user->company_name;

            $data = array(
                'document' => Document::findOrFail($id['0']),
                'pic' => User::whereNull('role_id')->where('company_name', $company_name)->get(),
                'alert' => TableAlert::get(),
                'title' => Title::where('is_delete', 0)->get(),
                'company' => Company::where('name', $company_name)->first(),
                'type' => HakiType::get(),
                'duty' => Duty::get(),
                // 'department' => getDepartment($company_name)
                'department' => Department::where('company_name', $company_name)->get(),
            );
            return view('main.tracking_haki.edit-draft')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function updateLegalDrafting(Request $request)
    {
        try {
            $id = $request->id;
            // Company check
            $company = Company::findOrFail($request->company);

            if ($request->unlimited_duration == 'on') {
                $effective_date = $request->contract_date;
            } else {

                // Get date 
                $duration_date = $request->duration;
                $exp_date = explode(' to ', $duration_date);
                $effective_date = $exp_date[0];
                $expired_date = $exp_date[1];

                // get difference
                $duration_days = Carbon::parse($effective_date)->diffInDays(Carbon::parse($expired_date));
            }

            // unlimited duration
            $unlimited_duration = $request->unlimited_duration == 'on' ? 1 : 0;
            $extend_automatically = $request->extend_automatically == 'on' ? 1 : 0;

            // Base Document
            $document = Document::findOrFail($request->id);

            // revision
            $sequence = $document->revision + 1;
            $document->revision = $sequence;

            // update document
            $document->description = $request->title;
            $document->priority = $request->priority;
            $document->contract_number = '-';
            $document->company = $company->name;
            $document->serial_number = 0;
            $document->duration = 0;
            $document->note = $request->note;
            $document->title_id = $request->title_sign;
            $document->haki_type_id = $request->type;
            $document->duty_id = $request->duty;
            $document->note = $request->note;
            $document->file = '-';

            // Document
            $document->created_by = Auth::user()->id;
            $document->is_extend = 0;
            $document->status = 0;

            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;

            $document->update();

            $dataHistory = array(
                'description' => $request->title,
                'priority' => $request->priority,
                'contract_number' => '-',
                'category' => 1,
                'company' => $company->name,
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'duration' => 0,
                'note' => $request->note,
                'file' => '-',
                'document_id' => $document->id,
                'serial_number' => 0,
                'created_by' => Auth::user()->id,
                'status' => 1,
                'revision' => $sequence,
                'title_id' => $request->title_sign,
                'haki_type_id' => $request->type,
                'duty_id' => $request->duty
            );

            $documentHistory = DocumentHistory::create($dataHistory);

            if (count($request->attachment) > 0) {
                for ($i = 0; $i < count($request->attachment); $i++) {

                    if ($request->attachment[$i] != '') {

                        $name = explode('.', request()->attachment[$i]->getClientOriginalName());

                        $fileName = $name[0] . '-' . time() . '.' . request()->attachment[$i]->getClientOriginalExtension();
                        request()->attachment[$i]->move(public_path('upload/drafting'), $fileName);

                        $data_upload = array(
                            'document_history_id' => $documentHistory->id,
                            'file' => $fileName
                        );
                        DocumentHistoryAttachment::create($data_upload);
                    }
                }
            }

            // PIC
            $pic_document = PicDocument::where('document_id', $document->id)->get();
            foreach ($pic_document as $pd) {
                $get_pic = PicDocument::findOrFail($pd->id);
                $get_pic->delete();
            }

            foreach ($request->pic as $item_pic) {
                $data_pic = array(
                    'document_id' => $document->id,
                    'user_id' => $item_pic
                );

                PicDocument::create($data_pic);
            }
            // END PIC

            // Document Scope
            $document_scope = DocumentScope::where('request_document_id', $document->request_document_id)->get();

            foreach ($document_scope as $key) {
                $get_dept = DocumentScope::findOrFail($key->id);
                $get_dept->delete();
            }

            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'request_document_id' => $document->request_document_id,
                    'document_id' => $document->id,
                    'department_code' => $scope[0],
                    'department_name' => $scope[1]
                );

                DocumentScope::create($data_scope);
            }

            $request_document = RequestDocument::findOrFail($document->request_document_id);
            $requester = User::findOrFail($request_document->created_by);
            $check = DocumentScope::where('request_document_id', $document->request_document_id)->where('department_code', $requester->dept)->count();

            if ($check < 1) {
                $data_scope_single = array(
                    'request_document_id' => $document->request_document_id,
                    'document_id' => $document->id,
                    'department_code' => $requester->dept,
                    'department_name' => $requester->department
                );

                DocumentScope::create($data_scope_single);
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking-haki.show', Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
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

    public function sendDraft($id)
    {
        try {
            $id = Hashids::decode($id);
            $request_document = RequestDocument::findOrFail($id['0']);
            $request_document->status_license = 1;
            $request_document->update();

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show-haki', 'Legal has created draft', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show-haki', 'Legal has created draft', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking-haki.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
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

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show-haki', 'Request getting feedback', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show-haki', 'Request getting feedback', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking-haki.show', Hashids::encode($request->id));
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

    public function registration($id)
    {
        try {
            $id = Hashids::decode($id);
            $request_document = RequestDocument::findOrFail($id['0']);
            $request_document->status = 2;
            $request_document->update();

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $id['0'],
                'step' => 2,
                'created_by' => Auth::user()->id,
                'step_name' => 'Registration'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show-haki', 'Request is continue to registration', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show-haki', 'Request is continue to registration', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking-haki.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function complete($id)
    {
        try {
            $id = Hashids::decode($id);
            $request_document = RequestDocument::findOrFail($id['0']);
            $request_document->status = 3;
            $request_document->update();

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $id['0'],
                'step' => 3,
                'created_by' => Auth::user()->id,
                'step_name' => 'Complete'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show-haki', 'Request is continue to complete', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show-haki', 'Request is continue to complete', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking-haki.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function documentFiling($id)
    {
        try {
            $id = Hashids::decode($id);

            $data = array(
                'document' => $id['0'],
                'doc' => Document::findOrFail($id['0']),
                'alert' => TableAlert::get()
            );
            return view('main.tracking_haki.create-ringkasan')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function filing(Request $request)
    {
        try {


            if ($request->unlimited_duration == 'on') {
                $effective_date = $request->contract_date;
                $start_alert_date = NULL;
            } else {

                // Get date 
                $duration_date = $request->duration;
                $exp_date = explode(' to ', $duration_date);
                $effective_date = $exp_date[0];
                $expired_date = $exp_date[1];

                // get difference
                $duration_days = Carbon::parse($effective_date)->diffInDays(Carbon::parse($expired_date));
                $start_alert_date = date('Y-m-d', strtotime($expired_date . ' - ' . $request->alert . ' days'));
            }

            // unlimited duration
            $unlimited_duration = $request->unlimited_duration == 'on' ? 1 : 0;
            $extend_automatically = $request->extend_automatically == 'on' ? 1 : 0;


            $document = Document::findOrFail($request->id);
            $document->title_ringkasan = $request->title;
            $document->ringkasan = $request->ringkasan;
            $document->contract_number = $request->haki_number;
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;
            $document->status = 1;

            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;

            $request_document = RequestDocument::findOrFail($document->request_document_id);
            $request_document->status = 4;
            $request_document->update();

            if ($request_document->is_extend == true) {

                // set all document by base to false
                $temp_doc = Document::where('base_document_id', $request_document->base_document_id)->get();
                foreach ($temp_doc as $key) {
                    $doc = Document::findOrFail($key->id);
                    $doc->is_extend = false;
                    $doc->update();
                }

                $base_document = BaseDocument::findOrFail($request_document->base_document_id);

                $base_document->description = $document->description;
                $base_document->priority = $document->priority;
                $base_document->contract_number = $request->haki_number;
                $base_document->company = $document->company;
                $base_document->pic = $document->pic;
                $base_document->pic_email = $document->pic_email;
                $base_document->pic_nrp = $document->pic_nrp;
                $base_document->pic_name = $document->pic_name;
                $base_document->contract_date = $effective_date;
                $base_document->deal_date = $effective_date;
                $base_document->serial_number = 0;
                $base_document->duration = 0;
                $base_document->note = $document->note;
                $base_document->is_extend = 0;
                $base_document->status = 1;
                $base_document->request_document_id = $document->request_document_id;
                $base_document->revision = 0;
                $base_document->title_id = $document->title_id;
                $base_document->haki_type_id = $document->haki_type_id;
                $base_document->duty_id = $document->duty_id;
                $base_document->launch_by = $document->launch_by;
                $base_document->title_ringkasan = $request->title;
                $base_document->ringkasan = $request->ringkasan;
                $base_document->is_proceed = false;
                $base_document->last_request_by = $request_document->created_by;

                $base_document->start_alert_date = $unlimited_duration == 1 ? NULL : $start_alert_date;
                $base_document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
                $base_document->is_extend_automatically = $extend_automatically;
                $base_document->is_unlimited_duration = $unlimited_duration;
                $base_document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
                $base_document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;

                $base_document->update();

                // Update document is extend true
                $document->is_extend = true;

                // Scope document
                $document_scope_base = DocumentScope::where('base_document_id', $request_document->base_document_id)->get();
                foreach ($document_scope_base as $dsb) {
                    $temp_dsb = DocumentScope::findOrFail($dsb->id);
                    $temp_dsb->delete();
                }

                $document_scope = DocumentScope::where('request_document_id', $document->request_document_id)->get();
                foreach ($document_scope as $key) {
                    $get_dept = DocumentScope::findOrFail($key->id);
                    $get_dept->base_document_id = $base_document->id;
                    $get_dept->update();
                }

                // delete old base document final
                BaseDocumentFinal::where('base_document_id', $base_document->id)->delete();
            } else {
                // Base Document
                $dataBase = array(
                    'description' => $document->description,
                    'priority' => $document->priority,
                    'contract_number' => $request->haki_number,
                    'category' => $document->category,
                    'company_id' => $document->company_id,
                    'company' => $document->company,
                    'pic' => $document->pic,
                    'pic_email' => $document->pic_email,
                    'pic_nrp' => $document->pic_nrp,
                    'pic_name' => $document->pic_name,
                    'contract_date' => $effective_date,
                    'deal_date' => $effective_date,
                    'serial_number' => 0,
                    'duration' => 0,
                    'note' => $document->note,
                    'created_by' => Auth::user()->id,
                    'is_extend' => 0,
                    'status' => 1,
                    'request_document_id' => $document->request_document_id,
                    'revision' => 0,
                    'title_id' => $document->title_id,
                    'haki_type_id' => $document->haki_type_id,
                    'duty_id' => $document->duty_id,
                    'launch_by' => $document->launch_by,
                    'title_ringkasan' => $request->title,
                    'ringkasan' => $request->ringkasan,
                    'last_request_by' => $request_document->created_by,

                    'start_alert_date' => $unlimited_duration == 1 ? NULL : $start_alert_date,
                    'end_contract_date' => $unlimited_duration == 1 ? NULL : $expired_date,
                    'is_extend_automatically' => $extend_automatically,
                    'is_unlimited_duration' => $unlimited_duration,
                    'duration_days' => $unlimited_duration == 1 ? NULL : $duration_days,
                    'alert_days' => $unlimited_duration == 1 ? NULL : $request->alert
                );

                $base_document = BaseDocument::create($dataBase);
                $document->is_extend = true;

                // Scope document
                $document_scope = DocumentScope::where('request_document_id', $document->request_document_id)->get();

                foreach ($document_scope as $key) {
                    $get_dept = DocumentScope::findOrFail($key->id);
                    $get_dept->base_document_id = $base_document->id;
                    $get_dept->update();
                }
            }

            // update document
            $document->base_document_id = $base_document->id;
            $document->update();

            // Document Final
            for ($i = 0; $i < count($request->file); $i++) {

                if ($request->file[$i] != '') {

                    $name = explode('.', request()->file[$i]->getClientOriginalName());

                    $fileName = $name[0] . '-' . time() . '.' . request()->file[$i]->getClientOriginalExtension();
                    request()->file[$i]->move(public_path('upload/document/contract'), $fileName);

                    $data_document = array(
                        'document_id' => $document->id,
                        'file' => $fileName
                    );

                    DocumentFinalAttachment::create($data_document);

                    $data_base = array(
                        'base_document_id' => $base_document->id,
                        'file' => $fileName
                    );

                    BaseDocumentFinal::create($data_base);
                }
            }

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $document->request_document_id,
                'step' => 4,
                'created_by' => Auth::user()->id,
                'step_name' => 'Filing'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show-haki', 'Request has done', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show-haki', 'Request has done', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking-haki.show',  Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    
    public function export()
    {
        $date = date('Y-m-d');
        return Excel::download(new ExportHakiRequest, 'HakiRequest_' . $date . '.xlsx');
    }
}
