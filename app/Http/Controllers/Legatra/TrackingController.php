<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert as TableAlert;
use App\Models\Table\AppName\MstDept;
use App\Models\Table\BaseDocument;
use App\Models\Table\BaseDocumentFinal;
use App\Models\Table\Company;
use App\Models\Table\Document;
use App\Models\Table\DocumentFinal;
use App\Models\Table\DocumentFinalAttachment;
use App\Models\Table\DocumentHistory;
use App\Models\Table\DocumentHistoryAttachment;
use App\Models\Table\DocumentScope;
use App\Models\Table\Feedback;
use App\Models\Table\Negotiation;
use App\Models\Table\Pic;
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
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use App\Exports\ExportContractRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Department;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('tracking') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('tracking')->r == 1) {
                $id_user = Auth::user()->id;
                $user = User::findOrFail($id_user);
                $company = $user->companyid;
                
               

                $filter = '';

                if (isset($request->filter)) {
                    if ($request->filter == 'progress') {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'Contract')->where('status', '!=', 7)->where('is_cancel', false)->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                        
                    } elseif ($request->filter == 'filing') {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'Contract')->where('status', 7)->where('is_cancel', false)->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    } elseif ($request->filter == 'cancel') {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'Contract')->where('is_cancel', true)->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    } else {
                        $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                        ->where('type', 'Contract')->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                        ->select('request_documents.*')->get();
                    }

                    $filter = $request->filter;
                } else {
                    $tracking = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
                    ->where('type', 'Contract')->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
                    ->select('request_documents.*')->get();
                    $filter = 'all';

                    // dd($tracking);
                }
              


                $data = [
                    'tracking' => $tracking,
                    'filter' => $filter
                ];

                // dd($data);

                return view('main.tracking.index')->with('data', $data);
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
        try {
            $id_user = Auth::user()->id;
            $user = User::findOrFail($id_user);
            $company = Company::where('company_id',$user->companyid)->first();

            $data = array(
                'pic' => Pic::where('company_id', $company->id)->get(),
            );

            return view('main.tracking.create')->with('data', $data);
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
            $request->validate([
                'file' => 'required',
            ]);

            $fileName = 'RequestDocument-' . time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('upload/request_document'), $fileName);

            $pic = Pic::findOrFail($request->email);

            $data = array(
                'title' => $request->title,
                'type' => $request->type,
                'para_pihak' => $request->para_pihak,
                'file' => $fileName,
                'scope' => $request->scope,
                'email' => $pic->email,
                'pic_id' => $request->email,
                'status' => 0,
                'created_by' => Auth::user()->id
            );

            $insert = RequestDocument::create($data);

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking.index');
        } catch (Exception $e) {
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
                'document' => UploadDocument::where('request_document_id', $id['0'])->get(),
                'expired' => TableAlert::get()
            );
            

            clickedNotification(Auth::user()->id, $id['0'], 'Request Contract/Letter');
            clickedNotification(Auth::user()->id, $id['0'], 'Draft Approved');
            clickedNotification(Auth::user()->id, $id['0'], 'Draft getting feedback');
            clickedNotification(Auth::user()->id, $id['0'], 'Draft Approved');
            clickedNotification(Auth::user()->id, $id['0'], 'Request continue to negotiation');

            return view('main.tracking.show')->with('data', $data);
        } catch (Exception $e) {
            // dd($e);
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
            $data = array(
                'tracking' => RequestDocument::findOrFail($id)
            );
            return view('main.tracking.edit')->with('data', $data);
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
            $tracking->para_pihak = $request->para_pihak;
            $tracking->scope = $request->scope;
            $tracking->email = $request->email;
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
            $tracking->update();

            Alert::success('Data Update Successfully', 'Success Message');

            return redirect()->route('closing-meeting.index');
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

    public function download($id)
    {
        $tracking = RequestDocument::findOrFail($id);
        $file = "upload/request_document/" . $tracking->file;
        $filePath = public_path($file);
        $fileName = $tracking->file;

        return response()->download($filePath, $fileName);
    }

    public function downloadAttachment($id)
    {
        $tracking = DocumentHistoryAttachment::findOrFail($id);
        $file = "upload/drafting/" . $tracking->file;
        $filePath = public_path($file);
        $fileName = $tracking->file;

        return response()->download($filePath, $fileName);
    }


    public function legalDrafting($id)
    {
        try {
            $id = Hashids::decode($id);
            $request_doc = RequestDocument::findOrFail($id['0']);
         

            $id_user = Auth::user()->id;
            $user = User::findOrFail($id_user);
            $companyid = $user->companyid;
          



            if ($request_doc->is_extend == true) {
                $data = array(
                    'tracking' => RequestDocument::findOrFail($id['0']),
                    // 'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                    'pic' => User::where('companyid', $companyid)->get(),
                    'alert' => TableAlert::get(),
                    // 'company' => Company::get(),
                    'company' => Company::where('company_id', $companyid)->first(),
                    'title' => Title::where('is_delete', 0)->get(),
                    'request_id' => $id['0'],
                    'base' => BaseDocument::findOrFail($request_doc->base_document_id),
                    // 'department' => getDepartment($companyid)
                    'department' => Department::where('company_id', $companyid)->get(),
                );
            } else {
                $data = array(
                    'tracking' => RequestDocument::findOrFail($id['0']),
                    // 'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                    'pic' => User::where('companyid', $companyid)->get(),
                    'alert' => TableAlert::get(),
                    // 'company' => Company::get(),
                    'company' => Company::where('company_id', $companyid)->first(),
                    'title' => Title::where('is_delete', 0)->get(),
                    'request_id' => $id['0'],
                    // 'department' => getDepartment($companyid)
                    'department' => Department::where('company_id', $companyid)->get(),
                );
            }

            

          

        


            return view('main.tracking.create-draft')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            // dd($e);
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

            // check penomoran
            $year = date("Y");
            $representative_year = substr($year, -2);
            $get_month = date("m");
            $month = getRomawiMonth($get_month);
            $start_date = $year . "-01-01";
            $end_date = $year . "-12-31";

            $get_sequence = Document::whereBetween('created_at', [$start_date, $end_date])->where('category', 1)->count();
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

            $company = Company::findOrFail($request->company);
            $title = Title::findOrFail($request->title);

            if ($request->category == 'Surat') {

                if ($request->jenis_surat == 'Surat Keluar') {
                    $jenis_surat = 'Let';
                    if ($request->tujuan_surat == 'Internal') {
                        $tujuan_surat = '1';
                    }
                    if ($request->tujuan_surat == 'Eksternal') {
                        $tujuan_surat = '2';
                    }
                    // $contract_number = 'Lgl.'.$title->code.'/'.$jenis_surat.'.'.$tujuan_surat.'/'.$company->short_name.'/'.$sequence.'/'.$month.'/'.$representative_year;
                    $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;

                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = $request->tujuan_surat;
                }
                if ($request->jenis_surat == 'Surat Kuasa') {
                    $jenis_surat = 'SK';

                    // $contract_number = 'Lgl.'.$title->code.'/'.$jenis_surat.'/'.$company->short_name.'/'.$sequence.'/'.$month.'/'.$representative_year;
                    $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;

                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = NULL;
                }
            } else {
                $jenis_surat = 'Agg';
                $document_type = $jenis_surat;
                $letter_type = NULL;
                $letter_purpose = NUll;
                // $contract_number = 'Lgl.'.$title->code.'/'.$jenis_surat.'/'.$company->short_name.'/'.$sequence.'/'.$month.'/'.$representative_year;
                $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $sequence . '/' . $month . '/' . $representative_year;
            }

            // Store data
            $data = array(
                'description' => $request->document_title,
                'priority' => $request->priority,
                // 'contract_number' => $contract_number,
                'contract_number' => '-',
                'category' => $category,
                'company_id' => $request->company,
                'company' => $company->name,
                // 'contract_date' => $request->contract_date,
                // 'deal_date' => $request->contract_date,
                'serial_number' => 0,
                'duration' => 0,
                'note' => $request->note,
                'issue' => $request->issue,
                'file' => '-',
                'created_by' => Auth::user()->id,
                'is_extend' => 0,
                'status' => 0,
                'request_document_id' => $id,
                'revision' => 0,
                'title_id' => $request->title,
                'document_type' => $document_type,
                'letter_type' => $letter_type,
                'letter_purpose' => $letter_purpose,
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
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'duration' => 0,
                'note' => $request->note,
                'file' => '-',
                'document_id' => $document->id,
                'serial_number' => 0,
                'created_by' => Auth::user()->id,
                'status' => 1,
                'revision' => 0,
                'title_id' => $request->title,
                'document_type' => $document_type,
                'letter_type' => $letter_type,
                'letter_purpose' => $letter_purpose,
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

            // Document Scope
            $document_scope = DocumentScope::where('request_document_id', $request_document->id)->get();

            foreach ($document_scope as $key) {
                $get_dept = DocumentScope::findOrFail($key->id);
                $get_dept->delete();
            }

            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'request_document_id' => $request_document->id,
                    'document_id' => $document->id,
                    'department_code' => $scope[0],
                    'department_name' => $scope[1]
                );

                DocumentScope::create($data_scope);
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

            $requester = User::findOrFail($request_document->created_by);
            $check = DocumentScope::where('request_document_id', $request_document->id)->where('department_code', $requester->dept)->count();

            if ($check < 1) {
                $data_scope_single = array(
                    'request_document_id' => $request_document->id,
                    'document_id' => $document->id,
                    'department_code' => $requester->dept,
                    'department_name' => $requester->department
                );

                DocumentScope::create($data_scope_single);
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($id));
        } catch (Exception $e) {
            // dd($e);
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
            $companyid = $user->companyid;

            $data = array(
                'document' => Document::findOrFail($id['0']),
                // 'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                'pic' => User::where('companyid', $companyid)->get(),
                'alert' => TableAlert::get(),
                'title' => Title::where('is_delete', 0)->get(),
                // 'company' => Company::get(),
                'company' => Company::where('company_id', $companyid)->first(),
                // 'department' => getDepartment($company_name)
                'department' => Department::where('company_id', $companyid)->get(),
            );
            return view('main.tracking.edit-draft')->with('data', $data);
        } catch (Exception $e) {
            // dd($e);
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


            if ($request->category == 'Surat') {

                if ($request->jenis_surat == 'Surat Keluar') {
                    $jenis_surat = 'Let';
                    if ($request->tujuan_surat == 'Internal') {
                        $tujuan_surat = '1';
                    }
                    if ($request->tujuan_surat == 'Eksternal') {
                        $tujuan_surat = '2';
                    }

                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = $request->tujuan_surat;
                }
                if ($request->jenis_surat == 'Surat Kuasa') {
                    $jenis_surat = 'SK';

                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = NULL;
                }
            } else {
                $jenis_surat = 'Agg';
                $document_type = $jenis_surat;
                $letter_type = NULL;
                $letter_purpose = NUll;
            }

            // update document
            $document = Document::findOrFail($request->id);
            $document->description = $request->title;
            $document->priority = $request->priority;
            // $document->contract_number = $contract_number;
            $document->company = $company->name;
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;
            $document->serial_number = 0;
            $document->duration = 0;
            $document->note = $request->note;
            $document->title_id = $request->title_sign;
            $document->document_type = $document_type;
            $document->letter_type = $letter_type;
            $document->letter_purpose = $letter_purpose;
            $document->issue = $request->issue;

            // revision
            $sequence = $document->revision + 1;
            $document->revision = $sequence;

            $document->created_by = Auth::user()->id;
            $document->is_extend = 0;
            $document->status = 0;

            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;
            $document->update();

            $dataHistory = array(
                'description' => $request->title,
                'priority' => $request->priority,
                // 'contract_number' => $contract_number,
                'contract_number' => '-',
                'category' => 1,
                'company' => $company->name,
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'duration' => 0,
                'note' => $request->note,
                'issue' => $request->issue,
                'file' => '-',
                'document_id' => $document->id,
                'serial_number' => 0,
                'created_by' => Auth::user()->id,
                'status' => 1,
                'revision' => $sequence,
                'title_id' => $request->title_sign,
                'document_type' => $document_type,
                'letter_type' => $letter_type,
                'letter_purpose' => $letter_purpose,
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
            return redirect()->route('tracking.show', Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            // dd($e);
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

    public function sendDraft(Request $request, $id)
    {
        try {
            $id = Hashids::decode($id);

            // $pdp = $request->is_pdp;
         
            // $expired = $request->expired_date;       

            $request_document = RequestDocument::findOrFail($id['0']);
            $request_document->status = 2;
            // $request_document->is_pdp = $pdp;
            $request_document->update();            
            

            //Expired Document
            // if($expired !== null){
            //     $alert = TableAlert::where('id', $expired)->first();
            //     $duration = $alert->duration;
            //     $expired_date = Carbon::now()->addDays($duration);  
            //     $expired_date_format = Carbon::now()->addDays($duration)->format('Y-m-d');
            
            //     Document::where('request_document_id', $id)->update(['expired_date' => $expired_date_format]);
            // }

       

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $id['0'],
                'step' => 2,
                'created_by' => Auth::user()->id,
                'step_name' => 'Send Draft'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show', 'Legal has created draft', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show', 'Legal has created draft', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
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

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($id['0']));
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

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($request->id));
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

    public function negotiationDraft(Request $request)
    {
        try {

            $data = array(
                'request_document_id' => $request->id,
                'document_id' => $request->id_document,
                'note' => $request->note,
                'created_by' => Auth::user()->id
            );

            Negotiation::create($data);

            $request_document = RequestDocument::findOrFail($request->id);
            $request_document->status = 6;
            $request_document->update();

            $document = Document::findOrFail($request->id_document);
            $document->is_negotiation = true;
            $document->update();

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $request->id,
                'step' => 6,
                'created_by' => Auth::user()->id,
                'step_name' => 'Negotiation'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show', 'Your Request continue to negotiation', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show', 'Your Request continue to negotiation', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($request->id));
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

    public function revisiDraft($id)
    {
        try {
            $id = Hashids::decode($id);
            $id_user = Auth::user()->id;
            $user = User::findOrFail($id_user);
            $companyid = $user->companyid;

            $data = array(
                'document' => Document::findOrFail($id['0']),
                // 'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                'pic' => User::where('companyid', $companyid)->get(),
                'alert' => TableAlert::get(),
                // 'company' => Company::get(),
                'company' => Company::where('company_id', $companyid)->first(),
                'title' => Title::where('is_delete', 0)->get(),
                // 'department' => getDepartment($company_name)
                'department' => Department::where('company_id', $companyid)->get(),
            );
            return view('main.tracking.revisi-draft')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function revisiUpdate(Request $request)
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

            // check penomoran

            if ($request->category == 'Surat') {

                if ($request->jenis_surat == 'Surat Keluar') {
                    $jenis_surat = 'Let';
                    if ($request->tujuan_surat == 'Internal') {
                        $tujuan_surat = '1';
                    }
                    if ($request->tujuan_surat == 'Eksternal') {
                        $tujuan_surat = '2';
                    }
                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = $request->tujuan_surat;
                }
                if ($request->jenis_surat == 'Surat Kuasa') {
                    $jenis_surat = 'SK';

                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = NULL;
                }
            } else {
                $jenis_surat = 'Agg';
                $document_type = $jenis_surat;
                $letter_type = NULL;
                $letter_purpose = NUll;
            }

            // update document
            $document = Document::findOrFail($request->id);
            $document->description = $request->title;
            $document->priority = $request->priority;
            // $document->contract_number = $contract_number;
            $document->company = $company->name;
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;
            $document->serial_number = 0;
            $document->duration = 0;
            $document->note = $request->note;
            $document->title_id = $request->title_sign;
            $document->document_type = $document_type;
            $document->letter_type = $letter_type;
            $document->letter_purpose = $letter_purpose;
            $document->alert_id = $request->duration;

            // revision
            $sequence = $document->revision + 1;
            $document->revision = $sequence;

            $document->created_by = Auth::user()->id;
            $document->is_extend = 0;
            $document->status = 0;
            $document->is_negotiation = false;


            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;
            $document->update();

            $dataHistory = array(
                'description' => $request->title,
                'priority' => $request->priority,
                // 'contract_number' => $contract_number,
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
                'document_type' => $document_type,
                'letter_type' => $letter_type,
                'letter_purpose' => $letter_purpose
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

            // next step
            $request_document = RequestDocument::findOrFail($document->request_document_id);

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $document->request_document_id,
                'step' => 4,
                'created_by' => Auth::user()->id,
                'step_name' => 'Revisi Draft'
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

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show', 'Legal has updated draft', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show', 'Legal has updated draft', $request_document->id);
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function sendRekanan($id)
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
                'step_name' => 'Send Rekanan'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show', 'Your Request continue to send rekanan', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show', 'Your Request continue to send rekanan', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($id['0']));
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function negotiation($id)
    {
        try {
            $id = Hashids::decode($id);
            $request_document = RequestDocument::findOrFail($id['0']);
            $request_document->status = 6;
            $request_document->update();

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $id['0'],
                'step' => 6,
                'created_by' => Auth::user()->id,
                'step_name' => 'Negotiation'
            );

            RequestDocumentActivity::create($dataActivity);

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($id['0']));
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
            return view('main.tracking.create-ringkasan')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function storeDocumentFiling(Request $request)
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
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;
            $document->status = 1;

            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;

            $request_document = RequestDocument::findOrFail($document->request_document_id);
            $request_document->status = 7;
            $request_document->update();

            // $alert = TableAlert::findOrFail($request->duration);

            // // add set alert date and end date
            // $end_contract_date = date('Y-m-d', strtotime($effective_date . ' + ' . $alert->duration . ' days'));
            // $start_alert_date = date('Y-m-d', strtotime($end_contract_date . ' - ' . $alert->start_alert . ' days'));

            if ($request_document->is_extend == true) {
                // set all document by base to false
                $temp_doc = Document::where('base_document_id', $request_document->base_document_id)->get();
                foreach ($temp_doc as $key) {
                    $doc = Document::findOrFail($key->id);
                    $doc->is_extend = false;
                    $doc->update();
                }

                $base_document = BaseDocument::findOrFail($request_document->base_document_id);

                $base_document->contract_number = $document->contract_number;
                $base_document->description = $document->description;
                $base_document->priority = $document->priority;
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
                $base_document->request_document_id = $document->request_document_id;
                $base_document->revision = 0;
                $base_document->title_id = $document->title_id;
                $base_document->title_ringkasan = $request->title;
                $base_document->ringkasan = $request->ringkasan;
                $base_document->document_type = $document->document_type;
                $base_document->letter_type = $document->letter_type;
                $base_document->letter_purpose = $document->letter_purpose;
                $base_document->status = 1;
                $base_document->last_request_by = $request_document->created_by;
                $base_document->is_proceed = false;
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
                // Store data
                $data = array(
                    'contract_number' => $document->contract_number,
                    'description' => $document->description,
                    'priority' => $document->priority,
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
                    'request_document_id' => $request_document->id,
                    'revision' => 0,
                    'title_id' => $document->title_id,
                    'title_ringkasan' => $request->title,
                    'ringkasan' => $request->ringkasan,
                    'status' => 1,
                    'document_type' => $document->document_type,
                    'letter_type' => $document->letter_type,
                    'letter_purpose' => $document->letter_purpose,
                    'last_request_by' => $request_document->created_by,
                    'start_alert_date' => $unlimited_duration == 1 ? NULL : $start_alert_date,
                    'end_contract_date' => $unlimited_duration == 1 ? NULL : $expired_date,
                    'is_extend_automatically' => $extend_automatically,
                    'is_unlimited_duration' => $unlimited_duration,
                    'duration_days' => $unlimited_duration == 1 ? NULL : $duration_days,
                    'alert_days' => $unlimited_duration == 1 ? NULL : $request->alert
                );

                $base_document = BaseDocument::create($data);
                $document->is_extend = true;

                $document_scope = DocumentScope::where('request_document_id', $document->request_document_id)->get();

                foreach ($document_scope as $key) {
                    $get_dept = DocumentScope::findOrFail($key->id);
                    $get_dept->base_document_id = $base_document->id;
                    $get_dept->update();
                }
            }

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
                'step' => 7,
                'created_by' => Auth::user()->id,
                'step_name' => 'Complete'
            );

            RequestDocumentActivity::create($dataActivity);

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show', 'Your Request has completed', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show', 'Your Request has completed', $request_document->id);
            }

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('tracking.show',  Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            // dd($e);
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

            // get id
            $temp_id = array();
            foreach ($document as $data) {
                $temp_id[] = $data->id;
            }

            foreach ($document as $key => $item) {
                $response .= "<tr>";
                $response .= "<td>" . $no . "</td>";
                $response .= "<td>" . $item->step_name . "</td>";
                $response .= "<td>" . getUserName($item->created_by)->name . "</td>";
                $response .= "<td>" . $item->created_at . "</td>";
                if ($key == 0) {
                    $response .= "<td> 0 Hari</td>";
                } else {
                    $previous_data = RequestDocumentActivity::find($temp_id[$key - 1]);

                    $start = strtotime($previous_data->created_at);
                    $end = strtotime($item->created_at);
                    $datediff = $end - $start;

                    $response .= "<td>" . round($datediff / (60 * 60 * 24)) . " Hari</td>";
                }
                $response .= "</tr>";

                $no++;
            }
            $response .= "</tbody></table>";

            return response()->json($response);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function revisiNegotiation($id)
    {
        try {
            $id = Hashids::decode($id);
            $id_user = Auth::user()->id;
            $user = User::findOrFail($id_user);
            $companyid = $user->companyid;

            $data = array(
                'document' => Document::findOrFail($id['0']),
                // 'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                'pic' => User::where('companyid', $companyid)->get(),
                'alert' => TableAlert::get(),
                // 'company' => Company::get(),
                'company' => Company::where('company_id', $companyid)->first(),
                'title' => Title::where('is_delete', 0)->get(),
                // 'department' => getDepartment($company_name)
                'department' => Department::where('company_id', $companyid)->get(),
            );
            return view('main.tracking.revisi-negotiation')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    public function revisiNegotiationUpdate(Request $request)
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

            // check penomoran

            if ($request->category == 'Surat') {

                if ($request->jenis_surat == 'Surat Keluar') {
                    $jenis_surat = 'Let';
                    if ($request->tujuan_surat == 'Internal') {
                        $tujuan_surat = '1';
                    }
                    if ($request->tujuan_surat == 'Eksternal') {
                        $tujuan_surat = '2';
                    }
                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = $request->tujuan_surat;
                }
                if ($request->jenis_surat == 'Surat Kuasa') {
                    $jenis_surat = 'SK';

                    $document_type = $request->category;
                    $letter_type = $request->jenis_surat;
                    $letter_purpose = NULL;
                }
            } else {
                $jenis_surat = 'Agg';
                $document_type = $jenis_surat;
                $letter_type = NULL;
                $letter_purpose = NUll;
            }

            // update document
            $document = Document::findOrFail($request->id);
            $document->description = $request->title;
            $document->priority = $request->priority;
            // $document->contract_number = $contract_number;
            $document->company = $company->name;
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;
            $document->serial_number = 0;
            $document->duration = 0;
            $document->note = $request->note;
            $document->title_id = $request->title_sign;
            $document->document_type = $document_type;
            $document->letter_type = $letter_type;
            $document->letter_purpose = $letter_purpose;
            $document->alert_id = $request->duration;

            // revision
            $sequence = $document->revision + 1;
            $document->revision = $sequence;

            $document->created_by = Auth::user()->id;
            $document->is_extend = 0;
            $document->status = 0;
            $document->is_negotiation = false;


            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;
            $document->update();

            $dataHistory = array(
                'description' => $request->title,
                'priority' => $request->priority,
                // 'contract_number' => $contract_number,
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
                'document_type' => $document_type,
                'letter_type' => $letter_type,
                'letter_purpose' => $letter_purpose
            );

            $documentHistory = DocumentHistory::create($dataHistory);

            if (isset($request->attachment)) {
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

            // next step
            $request_document = RequestDocument::findOrFail($document->request_document_id);

            // Acitivity
            $dataActivity = array(
                'request_document_id' => $document->request_document_id,
                'step' => 4,
                'created_by' => Auth::user()->id,
                'step_name' => 'Revisi Draft'
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

            // Notification
            if ($request_document->is_extend == 1) {
                addNotification($request_document->created_by, 'request-extend.show', 'Legal has updated draft', $request_document->id);
            } else {
                addNotification($request_document->created_by, 'request-document.show', 'Legal has updated draft', $request_document->id);
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('tracking.show', Hashids::encode($document->request_document_id));
        } catch (Exception $e) {
            // dd($e);
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function finalDownload($id)
    {
        $feedback = DocumentFinal::findOrFail($id);
        $file = "upload/document_final/" . $feedback->file;
        $filePath = public_path($file);
        $fileName = $feedback->file;

        return response()->download($filePath, $fileName);
    }

    public function cancel(Request $request)
    {
        try {
            $request_document = RequestDocument::findOrFail($request->id_cancel);
            $request_document->is_cancel = true;
            $request_document->update();

            Alert::success('Request Has Cancelled', 'Success Message');

            if ($request_document->type == 'Contract') {
                return redirect()->route('tracking.index');
            } elseif ($request_document->type == 'License') {
                return redirect()->route('tracking-license.index');
            } else {
                return redirect()->route('tracking-haki.index');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function syncrhonizeDocument()
    {
        try {
            $document = Document::get();
            foreach ($document as $key => $value) {
                $get_document = Document::findOrFail($value->id);

                // count duration
                $alert = TableAlert::findOrFail($value->alert_id);

                $get_document->duration_days = $alert->duration;
                $get_document->alert_days = $alert->start_alert;
                $get_document->end_contract_date = Carbon::parse($get_document->contract_date)->addDays($alert->duration);

                $get_document->update();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function syncrhonizeBaseDocument()
    {
        try {
            $document = BaseDocument::get();
            foreach ($document as $key => $value) {
                $get_document = BaseDocument::findOrFail($value->id);

                // count duration
                $alert = TableAlert::findOrFail($value->alert_id);

                $get_document->duration_days = $alert->duration;
                $get_document->alert_days = $alert->start_alert;

                $get_document->update();
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function sendReminderEmail($ids)
    {
        try {
            $id = Hashids::decode($ids);

            $request_document = RequestDocument::findOrFail($id[0]);
            $requester = User::findOrFail($request_document->created_by);

            $details = array(
                'type'  => $request_document->type,
                'title' => $request_document->title,
                'id'    => $ids,
            );

            if (filter_var($requester->email_sf, FILTER_VALIDATE_EMAIL) && $requester->email_sf != '' && $requester->email_sf != null) {
                sendReminder($requester->email_sf, $details);

                Alert::success('Reminder Email has been sent', 'Success Message');

                if ($request_document->type == 'Contract') {
                    return redirect()->route('tracking.show', $ids);
                } elseif ($request_document->type == 'License') {
                    return redirect()->route('tracking-license.show', $ids);
                } else {
                    return redirect()->route('tracking-haki.show', $ids);
                }
            }
        } catch (Exception $e) {
            // dd($e);
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function syncrhonizeBaseDocumentFile()
    {
        try {
            $document = BaseDocument::get();
            BaseDocumentFinal::truncate();
            foreach ($document as $key => $value) {
                $data = [
                    'file' => $value->file,
                    'base_document_id' => $value->id
                ];

                BaseDocumentFinal::create($data);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function syncrhonizeDocumentFile()
    {
        try {
            $document = Document::get();
            DocumentFinalAttachment::truncate();
            foreach ($document as $key => $value) {
                $data = [
                    'file' => $value->file,
                    'document_id' => $value->id
                ];

                DocumentFinalAttachment::create($data);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function negotiationApprove(Request $request, $id)
    {
        // try{
        $id = Hashids::decode($id);

        $data = array(
            'request_document_id' => $request->id,
            'document_id' => $id['0'],
            'created_by' => Auth::user()->id
        );

        Negotiation::create($data);

        $request_document = RequestDocument::findOrFail($request->id);
        $request_document->status = 6;
        $request_document->update(); 

        // Acitivity
        $dataActivity = array(
            'request_document_id' => $request->id,
            'step' => 6,
            'created_by' => Auth::user()->id,
            'step_name' => 'Negotiation'
        );

        $document = Document::findOrFail($id['0']);
        // $document->is_negotiation = false;
        // $document->is_final = true;

        // check penomoran
        $year = date("Y");
        $representative_year = substr($year, -2);
        $get_month = date("m");
        $month = getRomawiMonth($get_month);
        $start_date = $year . "-01-01";
        $end_date = $year . "-12-31";

        $reset_company_name = 'PT Patria Maritim Perkasa';

        // Cek apakah perlu reset sequence dari 001
        if ($document->company === $reset_company_name) {
            // Misalnya reset dimulai dari awal tahun
            // $reset_start_date = $year . "-05-07";
            $reset_start_date = $year . "-01-06";
        } else {
            // Untuk perusahaan lain tetap dari awal tahun
            $reset_start_date = $year . "-01-01";
        }
        
        $reset_end_date = $year . "-12-31";

        $get_seq_temp = Document::whereBetween('created_at', [$reset_start_date, $reset_end_date])->where('category', 1)->where('status', 1)
        ->where('company', $document->company)
        ->count();
        $get_sequence = $get_seq_temp + 1;

        // Pembuatan nomor kontrak versi lama
        // if ($get_sequence < 100) {
        //     if ($get_sequence == 0) {
        //         $sequence = '001';
        //     } else {
        //         if ($get_sequence < 10) {
        //             $sequence = '00' . $get_sequence;
        //         } else {
        //             $sequence = '0' . $get_sequence;
        //         }
        //     }
        // } else {
        //     $sequence = $get_sequence;
        // }

        // $company = Company::where('name', $document->company)->first();
        // $title = Title::findOrFail($document->title_id);

        // if ($document->document_type == 'Surat') {

        //     if ($document->letter_type == 'Surat Keluar') {
        //         $jenis_surat = 'Let';

        //         $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;
        //     }
        //     if ($document->letter_type == 'Surat Kuasa') {
        //         $jenis_surat = 'SK';

        //         $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;
        //     }
        // } else {
        //     $jenis_surat = 'Agg';

        //     $contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $sequence . '/' . $month . '/' . $representative_year;
        // }

        // $document->contract_number = $contract_number;
        // $document->status = 1;
        // $document->update();
        // $document->save();
        
        // Pembuatan nomor kontrak versi baru
        // Cek dan increment jika nomor sudah ada
        do {
            // Format sequence sesuai aturan
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


            // Bentuk nomor kontrak sementara (sesuai aturanmu)
            if ($document->document_type == 'Surat') {
                if ($document->letter_type == 'Surat Keluar') {
                    $jenis_surat = 'Let';
                } elseif ($document->letter_type == 'Surat Kuasa') {
                    $jenis_surat = 'SK';
                }
                $temp_contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $title->code . '/' . $sequence . '/' . $month . '/' . $representative_year;
            } else {
                $jenis_surat = 'Agg';
                $temp_contract_number = $jenis_surat . '/' . $company->short_name . '-CL/' . $sequence . '/' . $month . '/' . $representative_year;
            }

            // Cek apakah sudah ada di DB pada tahun yang sama
            $exists = Document::whereYear('updated_at', $year)
                ->where('company', $document->company)
                ->where('contract_number', $temp_contract_number)
                ->exists();

            if ($exists) {
                $get_sequence++; // Naikkan nomor dan cek lagi
            }

        } while ($exists); 

        $document->update([
            'is_negotiation' => false,
            'is_final' => true,
            'contract_number' => $temp_contract_number,
            'status' => 1,
        ]);

        addNotification($document->RequestDocument->Pic->user_id, 'tracking.show', 'Draft Approved', $document->request_document_id);

        Alert::success('Data Update Successfully', 'Success Message');
        return redirect()->route('tracking.show', Hashids::encode($document->request_document_id));

        // } catch (Exception $e) {
        //     $this->ErrorLog($e);
        //     return redirect()->back()->with('error', 'Error Request, Exception Error ');
        // }
    }

    public function export()
    {
        $date = date('Y-m-d');
        return Excel::download(new ExportContractRequest, 'ContractRequest_' . $date . '.xlsx');
    }
}
