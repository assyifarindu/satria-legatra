<?php

namespace App\Http\Controllers\Legatra;

use App\Exports\ExportDocuments;
use App\Exports\ExportLicense;
use App\Http\Controllers\Controller;
use App\Models\Table\Alert as TableAlert;
use App\Models\Worklocation;
use App\Models\Table\BaseDocument;
use App\Models\Table\BaseDocumentActivity;
use App\Models\Table\BaseDocumentFinal;
use App\Models\Table\Company;
use App\Models\Table\Document;
use App\Models\Table\DocumentActivityHistory;
use App\Models\Table\DocumentFinalAttachment;
use App\Models\Table\DocumentScope;
use App\Models\Table\ExtendedDocument;
use App\Models\Table\PicDocument;
use App\Models\Table\Title;
use App\Models\User;
use App\Models\View\VwPicDocument;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\Department;


class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('license') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $user = User::where('id', Auth::user()->id)->first();
            $company_name = $user->company_name;

            $data = [
                // 'license' => Document::where('category', 2)->where('is_extend', 0)->where('status', 1)->orderBy('created_at', 'desc')->get(),
                'license' => BaseDocument::where('category', 2)->where('is_extend', 0)->where('status', 1)->where('company', $company_name)->orderBy('created_at', 'desc')->get(),
                'inactive_license' => BaseDocument::where('category', 2)->where('is_extend', 0)->where('status', 0)->where('company', $company_name)->orderBy('deleted_at', 'desc')->get(),
                'duration' => TableAlert::get()
            ];

            return view('main.license_base.index')->with('data', $data);
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
            $companyid = $user->companyid;

            $data = array(
                // 'pic' => User::whereNull('role_id')->get(),
                'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                'alert' => TableAlert::get(),
                // 'company' => Company::get(),
                'company' => Company::where('company_id', $companyid)->first(),
                'title' => Title::where('is_delete', 0)->get(),
                // 'department' => getDepartment()
                // 'department' => getDepartment($company_name),
                'department' => Department::where('company_id', $companyid)->get(),
                'worklocation' => Worklocation::where('company_id', $companyid)->get()

            );

            return view('main.license_base.create')->with('data', $data);
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
        // try {
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
                $end_contract_date = date('Y-m-d', strtotime($request->contract_date . ' + ' . $request->duration . ' days'));
                $start_alert_date = date('Y-m-d', strtotime($end_contract_date . ' - ' . $request->start_alert . ' days'));
            }

            // unlimited duration
            $unlimited_duration = $request->unlimited_duration == 'on' ? 1 : 0;
            $extend_automatically = $request->extend_automatically == 'on' ? 1 : 0;

            $worklocation = Worklocation::where('id', $request->worklocation)->first();

            $data_base = array(
                'contract_number' => $request->contract_number,
                'description' => $request->document_title,
                'priority' => $request->priority,
                'category' => 2,
                'company_id' => $request->company,
                'company' => $company->name,
                // 'alert_id' => $request->duration,
                'serial_number' => 0,
                'duration' => 0,
                'note' => $request->note,
                'created_by' => Auth::user()->id,
                'is_extend' => 0,
                'revision' => 0,
                'title_id' => $request->title,
                'title_ringkasan' => $request->title_ringkasan,
                'ringkasan' => $request->ringkasan,
                'status' => 1,
                'last_request_by' => Auth::user()->id,
                'created_at' => $effective_date,
                'updated_at' => $effective_date,

                'start_alert_date' => $unlimited_duration == 1 ? NULL : $start_alert_date,
                'alert_days' => $unlimited_duration == 1 ? NULL : $request->alert,
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'end_contract_date' => $unlimited_duration == 1 ? NULL : $expired_date,
                'is_extend_automatically' => $extend_automatically,
                'is_unlimited_duration' => $unlimited_duration,
                'duration_days' => $unlimited_duration == 1 ? NULL : $duration_days,
                'id_worklocation' => $worklocation->id,
                'worklocation' => $worklocation->worklocation_code
            );
         

            $base_document = BaseDocument::create($data_base);
            // dd($base_document);

            // Store data
            $data = array(
                'description' => $request->document_title,
                'priority' => $request->priority,
                'contract_number' => $request->contract_number,
                'category' => 2,
                'company' => $company->name,
                'serial_number' => 0,
                'duration' => 0,
                'note' => $request->note,
                'created_by' => Auth::user()->id,
                'is_extend' => 1,
                'is_final' => 1,
                'status' => 1,
                'revision' => 0,
                'title_id' => $request->title,
                'title_ringkasan' => $request->title_ringkasan,
                'ringkasan' => $request->ringkasan,
                'request_by' => Auth::user()->id,
                // 'alert_id' => $request->duration,
                'created_at' => $effective_date,
                'updated_at' => $effective_date,
                'base_document_id' => $base_document->id,

                'start_alert_date' => $unlimited_duration == 1 ? NULL : $start_alert_date,
                'alert_days' => $unlimited_duration == 1 ? NULL : $request->alert,
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'end_contract_date' => $unlimited_duration == 1 ? NULL : $expired_date,
                'is_extend_automatically' => $extend_automatically,
                'is_unlimited_duration' => $unlimited_duration,
                'duration_days' => $unlimited_duration == 1 ? NULL : $duration_days,
                'id_worklocation' => $worklocation->id,
                'worklocation' => $worklocation->worklocation_code
            );

            $document = Document::create($data);


            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'document_id' => $document->id,
                    'base_document_id' => $base_document->id,
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

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('license.index');
        // } catch (Exception $e) {
        //     $this->ErrorLog($e);
        //     return redirect()->back()->with('error', 'Error Request, Exception Error ');
        // }
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

            $data = [
                'contract' => BaseDocument::findOrFail($id['0'])
            ];

            $dataAvtivity = array(
                'document_id' => $id['0'],
                'user_id' => Auth::user()->id,
                'activity_name' => 'Access Preview Document'
            );

            DocumentActivityHistory::create($dataAvtivity);

            return view('main.license_base.show')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function detail($id)
    {
        try {

            $id = Hashids::decode($id);

            $data = [
                'contract' => BaseDocument::findOrFail($id['0']),
                'extend-contract' => Document::where('base_document_id', $id['0'])->orderBy('created_at', 'desc')->get(),
                'current_contract' => Document::where('base_document_id', $id['0'])->where('is_extend', true)->first(),
            ];

            $dataAvtivity = array(
                'base_document_id' => $id['0'],
                'user_id' => Auth::user()->id,
                'activity_name' => 'Access Preview Document'
            );

            BaseDocumentActivity::create($dataAvtivity);

            return view('main.license_base.detail')->with('data', $data);
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
         
            $companyid = $user->companyid;


            $data = array(
                // 'document' => BaseDocument::findOrFail($id['0']),
                'document' => Document::where('is_extend', 1)->where('base_document_id', $id['0'])->first(),
                // 'pic' => User::whereNull('role_id')->get(),
                'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                'alert' => TableAlert::get(),
                // 'company' => Company::get(),
                'company' => Company::where('company_id', $companyid)->first(),
                'title' => Title::where('is_delete', 0)->get(),
                // 'department' => getDepartment()
                // 'department' => getDepartment($company_name),
                'department' => Department::where('company_id', $companyid)->get(),
                'worklocation' => Worklocation::where('company_id', $companyid)->get()
            );

            return view('main.license_base.extend')->with('data', $data);
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
            // Company check
            $company = Company::findOrFail($request->company);

            $id = Hashids::decode($id);

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
                $end_contract_date = date('Y-m-d', strtotime($request->contract_date . ' + ' . $request->duration . ' days'));
                $start_alert_date = date('Y-m-d', strtotime($end_contract_date . ' - ' . $request->start_alert . ' days'));
            }

            $worklocation = Worklocation::where('id', $request->worklocation)->first();

            // unlimited duration
            $unlimited_duration = $request->unlimited_duration == 'on' ? 1 : 0;
            $extend_automatically = $request->extend_automatically == 'on' ? 1 : 0;

            $base_document = BaseDocument::findOrFail($id['0']);

            $base_document->contract_number = $request->contract_number;
            $base_document->description = $request->document_title;
            $base_document->priority = $request->priority;
            $base_document->category = 2;
            $base_document->company_id = $request->company;
            $base_document->company = $company->name;
            $base_document->contract_date = $effective_date;
            $base_document->deal_date = $effective_date;
            $base_document->serial_number = 0;
            $base_document->duration = 0;
            $base_document->note = $request->note;
            // $base_document->file = $fileName;
            $base_document->created_by = Auth::user()->id;
            $base_document->is_extend = 0;
            $base_document->revision = 0;
            $base_document->title_id = $request->title;
            $base_document->title_ringkasan = $request->title_ringkasan;
            $base_document->ringkasan = $request->ringkasan;
            $base_document->status = 1;
            $base_document->last_request_by = Auth::user()->id;
            $base_document->created_at = $request->contract_date;
            $base_document->updated_at = $request->contract_date;

            $base_document->start_alert_date = $unlimited_duration == 1 ? NULL : $start_alert_date;
            $base_document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $base_document->is_extend_automatically = $extend_automatically;
            $base_document->is_unlimited_duration = $unlimited_duration;
            $base_document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $base_document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;

            $base_document->id_worklocation = $worklocation->id;
            $base_document->worklocation = $worklocation->worklocation_code;

            $base_document->update();

            $temp_doc = Document::where('base_document_id', $id['0'])->get();
            foreach ($temp_doc as $key) {
                $doc = Document::findOrFail($key->id);
                $doc->is_extend = false;
                $doc->update();
            }

            // Store data
            $data = array(
                'description' => $request->document_title,
                'priority' => $request->priority,
                'contract_number' => $request->contract_number,
                'category' => 2,
                'company' => $company->name,
                'serial_number' => 0,
                'duration' => 0,
                'note' => $request->note,
                'created_by' => Auth::user()->id,
                'is_extend' => 1,
                'is_final' => 1,
                'status' => 1,
                'revision' => 0,
                'title_id' => $request->title,
                'title_ringkasan' => $request->title_ringkasan,
                'ringkasan' => $request->ringkasan,
                'request_by' => Auth::user()->id,
                'created_at' => $request->contract_date,
                'updated_at' => $request->contract_date,
                'base_document_id' => $base_document->id,

                'alert_days' => $unlimited_duration == 1 ? NULL : $request->alert,
                'contract_date' => $effective_date,
                'deal_date' => $effective_date,
                'end_contract_date' => $unlimited_duration == 1 ? NULL : $expired_date,
                'is_extend_automatically' => $extend_automatically,
                'is_unlimited_duration' => $unlimited_duration,
                'duration_days' => $unlimited_duration == 1 ? NULL : $duration_days,
                'id_worklocation' => $worklocation->id,
                'worklocation' => $worklocation->worklocation_code
            );

            $document = Document::create($data);

            $document_scope_base = DocumentScope::where('base_document_id', $id['0'])->get();
            foreach ($document_scope_base as $dsb) {
                $temp_dsb = DocumentScope::findOrFail($dsb->id);
                $temp_dsb->delete();
            }

            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'document_id' => $document->id,
                    'base_document_id' => $base_document->id,
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

                    BaseDocumentFinal::where('base_document_id', $base_document->id)->delete();

                    $data_base = array(
                        'base_document_id' => $base_document->id,
                        'file' => $fileName
                    );

                    BaseDocumentFinal::create($data_base);
                }
            }

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('license.index');
        } catch (Exception $e) {
            // dd($e);
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
        try {
            $id = Hashids::decode($id);
            $document = BaseDocument::findOrFail($id['0']);
            $document->status = 0;

            $user = User::where('id', Auth::user()->id)->first();
            $document->updated_by = $user->id;
            $document->deleted_at = Carbon::now();
            $document->deleted_by_name = $user->name;


            $document->update();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('license.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function editDocument($id)
    {
        try {
            $id = Hashids::decode($id);
            $user = User::where('id', Auth::user()->id)->first();
            
           
            $companyid = $user->companyid;

        
            $data = array(
                // 'document' => BaseDocument::findOrFail($id['0']),
                'document' => Document::where('is_extend', 1)->where('base_document_id', $id['0'])->first(),
                // 'pic' => User::whereNull('role_id')->get(),
                'pic' => User::whereNull('role_id')->where('companyid', $companyid)->get(),
                'alert' => TableAlert::get(),
                // 'company' => Company::get(),
                'company' => Company::where('company_id', $companyid)->first(),
                'title' => Title::where('is_delete', 0)->get(),
                // 'department' => getDepartment()
                // 'department' => getDepartment($company_name),
                'department' => Department::where('company_id', $companyid)->get(),
                'worklocation' => Worklocation::where('company_id', $companyid)->get()
            );

            return view('main.license_base.edit')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function updateDocument(Request $request, $id)
    {
        try {
            // Company check
            $company = Company::findOrFail($request->company);

            $id = Hashids::decode($id);

            $fileName = '-';
            // File
            if ($request->file) {
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'Document-' . time() . '.' . request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/document/contract'), $fileName);
            } else {
                $base = BaseDocument::findOrFail($id['0']);
                $fileName = $base->file;
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
                $end_contract_date = date('Y-m-d', strtotime($request->contract_date . ' + ' . $request->duration . ' days'));
                $start_alert_date = date('Y-m-d', strtotime($end_contract_date . ' - ' . $request->start_alert . ' days'));
            }

            $worklocation = Worklocation::where('id', $request->worklocation)->first();

            // unlimited duration
            $unlimited_duration = $request->unlimited_duration == 'on' ? 1 : 0;
            $extend_automatically = $request->extend_automatically == 'on' ? 1 : 0;

            $base_document = BaseDocument::findOrFail($id['0']);

            $base_document->contract_number = $request->contract_number;
            $base_document->description = $request->document_title;
            $base_document->priority = $request->priority;
            $base_document->category = 2;
            $base_document->company_id = $request->company;
            $base_document->company = $company->name;
            $base_document->contract_date = $effective_date;
            $base_document->deal_date = $effective_date;
            $base_document->serial_number = 0;
            $base_document->duration = 0;
            $base_document->note = $request->note;
            $base_document->file = $fileName;
            $base_document->created_by = Auth::user()->id;
            $base_document->is_extend = 0;
            $base_document->revision = 0;
            $base_document->title_id = $request->title;
            $base_document->title_ringkasan = $request->title_ringkasan;
            $base_document->ringkasan = $request->ringkasan;
            $base_document->status = 1;
            $base_document->last_request_by = Auth::user()->id;
            $base_document->created_at = $request->contract_date;
            $base_document->updated_at = $request->contract_date;

            $base_document->start_alert_date = $unlimited_duration == 1 ? NULL : $start_alert_date;
            $base_document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $base_document->is_extend_automatically = $extend_automatically;
            $base_document->is_unlimited_duration = $unlimited_duration;
            $base_document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $base_document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;

            $base_document->id_worklocation = $worklocation->id;
            $base_document->worklocation = $worklocation->worklocation_code;

            $base_document->update();

            $get_document = Document::where('base_document_id', $id['0'])->where('is_extend', 1)->first();
            $document = Document::findOrFail($get_document->id);

            // Store data
            $document->description = $request->document_title;
            $document->priority = $request->priority;
            $document->contract_number = $request->contract_number;
            $document->category = 2;
            $document->company = $company->name;
            $document->contract_date = $effective_date;
            $document->deal_date = $effective_date;
            $document->serial_number = 0;
            $document->duration = 0;
            $document->note = $request->note;
            $document->file = $fileName;
            $document->created_by = Auth::user()->id;
            $document->is_extend = 1;
            $document->is_final = 1;
            $document->status = 1;
            $document->revision = 0;
            $document->title_id = $request->title;
            $document->title_ringkasan = $request->title_ringkasan;
            $document->ringkasan = $request->ringkasan;
            $document->request_by = Auth::user()->id;
            $document->created_at = $request->contract_date;
            $document->updated_at = $request->contract_date;
            $document->base_document_id = $base_document->id;

            $document->end_contract_date = $unlimited_duration == 1 ? NULL : $expired_date;
            $document->is_extend_automatically = $extend_automatically;
            $document->is_unlimited_duration = $unlimited_duration;
            $document->duration_days = $unlimited_duration == 1 ? NULL : $duration_days;
            $document->alert_days = $unlimited_duration == 1 ? NULL : $request->alert;

            $document->id_worklocation = $worklocation->id;
            $document->worklocation = $worklocation->worklocation_code;

            $document->update();

            $document_scope_base = DocumentScope::where('base_document_id', $id['0'])->get();
            foreach ($document_scope_base as $dsb) {
                $temp_dsb = DocumentScope::findOrFail($dsb->id);
                $temp_dsb->delete();
            }

            for ($i = 0; $i < count($request->doc_scope); $i++) {
                $scope = explode('-', $request->doc_scope[$i]);

                $data_scope = array(
                    'document_id' => $document->id,
                    'base_document_id' => $base_document->id,
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

            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()->route('license.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function export()
    {
        $date = date('Y-m-d');

        return Excel::download(new ExportLicense, 'LicenseFiling_' . $date . '.xlsx');
    }
}
