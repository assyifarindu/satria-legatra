<?php

namespace App\Http\Controllers\Legatra;

use App\Helpers\GenerateDocumentNumber;
use App\Http\Controllers\Controller;
use App\Models\Table\Company;
use App\Models\Table\Department;
use App\Models\Table\Document;
use App\Models\Table\GenerateNumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class GenerateNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $documentNumbers = GenerateNumber::orderBy('id', 'desc')->get();
            return view('main.generate-number.index', [
                'documentNumbers' => $documentNumbers,
            ]);
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
        try {
            $companies = Company::all();
            return view('main.generate-number.create', [
                'companies' => $companies,
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
        $validation = Validator::make($request->all(), [
            'user' => 'required|string',
            'create_by' => 'required|string',
            'nrp' => 'required|string',
            'document_type' => 'required|string',
            'company' => 'required|string',
            'department' => 'required|string',
            'title' => 'required|string',
            'receipent' => 'required|string',
        ]);

        if ($validation->fails()) {
            return redirect()->route('generate-number.create')
            ->withErrors($validation)
            ->withInput(); 
        } 

        $companyId = $request->input('company');
        $departmentId = $request->input('department');
        $documentType = $request->input('document_type');

        $department = Department::where('id', $departmentId)->first();
        $company = Company::where('short_name', $companyId)->first();

        $documents = GenerateNumber::orderBy('id', 'ASC')->get();
        $isFindSame = false;
        $lastNumberDocument = "";
        $resultGenerate = "";
        
        foreach ($documents as $data) {
            if($data->document_type == $documentType && $data->company_id == $company->id && $data->department_id == $departmentId){
                $isFindSame = true;
                $lastNumberDocument = $data->last_number;
            }
        }

        $signBy = $request->input('sign_by');
        $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
        request()->file('file')->move(public_path('storage/document/numbers'), $fileName);
        $pathFile = 'document/numbers/' . $fileName;

        if($isFindSame == true){
            $resultGenerate = GenerateDocumentNumber::generateNumber($documentType, $company->short_name, $department->short_name, $signBy, $lastNumberDocument);
        }else{
            $resultGenerate = GenerateDocumentNumber::generateNumber($documentType, $company->short_name, $department->short_name, $signBy);
        }

        $data = [
            'user' => $request->input('user'),
            'create_by' => $request->input('create_by'),
            'nrp' => $request->input('nrp'),
            'document_type' => $request->input('document_type'),
            'company_id' => $company->id,
            'department_id' => $departmentId,
            'document_title' => $request->input('title'),
            'file' => "/$pathFile",
            'receipent' => $request->input('receipent'),
            'document_number' => $resultGenerate['generateDocumentNumber'],
            'last_number' => $resultGenerate['numberDocument'],
            'sign_by' => $signBy
        ];

        GenerateNumber::create($data);

        return redirect()->route('generate-number.index')->with('success', 'Berhasil menambahkan data');
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
            $documentNumber = GenerateNumber::findOrFail($id[0]);

            return view('main.generate-number.show', [
                'document' => $documentNumber
            ]);

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
        try {
            $id = Hashids::decode($id);
            $documentNumbers = GenerateNumber::findOrFail($id[0]);
            $companies = Company::all();
            $departments = Department::all();

            $documentTypes = [
                array('value' => 'Agg', 'name' => 'Agreement (Agg)'),
                array('value' => 'Let', 'name' => 'Surat (Let)'),
                array('value' => 'SK', 'name' => 'Surat Kuasa (SK)'),
                array('value' => 'Memo', 'name' => 'Memo (Memo)'),
                array('value' => 'SOP', 'name' => 'Standard Operational Procedure (SOP)'),
             ];

             
            return view('main.generate-number.edit', [
                'companies' => $companies,
                'departments' => $departments,
                'documentNumber' => $documentNumbers,
                'documentTypes' => $documentTypes,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $generateNumber = GenerateNumber::findOrFail($id);

            $validation = Validator::make($request->all(), [
                'user' => 'required|string',
                'create_by' => 'required|string',
                'nrp' => 'required|string',
                'document_type' => 'required|string',
                'company' => 'required|string',
                'department' => 'required|string',
                'title' => 'required|string',
                'receipent' => 'required|string',
            ]);
    
            if ($validation->fails()) {
                return redirect()->route('generate_number.create')
                ->withErrors($validation)
                ->withInput(); 
            } 
    
            $companyId = $request->input('company');
            $departmentId = $request->input('department');
            $documentType = $request->input('document_type');
    
            $department = Department::where('id', $departmentId)->first();
            $company = Company::where('short_name', $companyId)->first();
            
            $documents = GenerateNumber::orderBy('id', 'ASC')->get();
            $isFindSame = false;
            $lastNumberDocument = "";
            $resultGenerate = "";
    
            foreach ($documents as $data) {
                if($data->document_type == $documentType && $data->company_id == $companyId && $data->department_id == $departmentId){
                    $isFindSame = true;
                    $lastNumberDocument = $data->last_number;
                }
            }
    
            $signBy = $request->input('sign_by');
    
    
            if($request->hasFile('file')){
                $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
                request()->file('file')->move(public_path('storage/document/numbers'), $fileName);
                $pathFile = 'document/numbers/' . $fileName;
                Storage::disk('public')->delete([$generateNumber->file]);
            }else{
                $pathFile = $generateNumber->file;
            }
    
            if($isFindSame == true){
                $resultGenerate = GenerateDocumentNumber::generateNumber($documentType, $company->short_name, $department->short_name, $signBy, $lastNumberDocument);
            }else{
                $resultGenerate = GenerateDocumentNumber::generateNumber($documentType, $company->short_name, $department->short_name, $signBy);
            }
    
            $data = [
                'user' => $request->input('user'),
                'create_by' => $request->input('create_by'),
                'nrp' => $request->input('nrp'),
                'document_type' => $request->input('document_type'),
                'company_id' => $company->id,
                'department_id' => $departmentId,
                'document_title' => $request->input('title'),
                'file' => "$pathFile",
                'receipent' => $request->input('receipent'),
                'document_number' => $resultGenerate['generateDocumentNumber'],
                'last_number' => $resultGenerate['numberDocument'],
                'sign_by' => $signBy
            ];
    
            $generateNumber->update($data);
    
            return redirect()->route('generate-number.index')->with('success', 'Berhasil mengubah data');
        } catch (Exception $e) {
            $this->ErrorLog($e);
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

        $generateNumber = GenerateNumber::findOrFail($id);
        Storage::disk('public')->delete([$generateNumber->file]);
        $generateNumber->delete();
        return redirect()->route('generate-number.index')->with('success', 'Berhasil menghapus data');
    }

    public function getDepartments($companyCode)
    {
        $departments = Department::whereJsonContains('company_codes', $companyCode)->get();
        return response()->json($departments);
    }
}
