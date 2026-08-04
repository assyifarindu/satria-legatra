<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert;
use App\Models\Table\Document;
use App\Models\Table\GenerateNumber;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Table\Company;
use Illuminate\Support\Facades\Auth;

class ContractNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('contract-number') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{
            $user = User::where('id', Auth::user()->id)->first();
	    $company_id = $user->companyid;
            
            $company = Company::where('company_id', $company_id)->first();
	    $company_name = $company->name;
            
            
            $generateNumbers = GenerateNumber::orderBy('generate_numbers.id', 'desc')->join('companies', 'generate_numbers.company_id', '=', 'companies.id')
            ->where('companies.name', $company_name)
            ->withTrashed()
            ->get(); 
            
            $countGenerateNumber = GenerateNumber::count();
            //$documents = Document::where('category', 1)->where('company', $company_name)->orderBy('created_at', 'desc')->get();
	    $documents = Document::select(
                'documents.*',
                'request_documents.title as request_title'
            )
            ->leftJoin('request_documents', 'documents.request_document_id', '=', 'request_documents.id')
            ->where('documents.category', 1)
            ->where('documents.company', $company_name)
            ->orderBy('documents.created_at', 'desc')
            ->get();

            if($countGenerateNumber > 0){
                $combinedResults = collect($generateNumbers)->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'type' => 'generate_number',
                        'contract_number' => $item->document_number,
                        'base_document_id' => null,
                        'description' => $item->document_title,
                        'company' => $item->company->name ?? null,
                        'document_type' => $item->document_type,
                        'contract_date' => $item->created_at,
			'request_title' => null
                    ];
                })->merge($documents->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'contract_number' => $item->contract_number,
                        'base_document_id' => $item->base_document_id,
                        'description' => $item->description,
                        'company' => $item->company ?? null,
                        'document_type' => $item->document_type,
                        'contract_date' => $item->contract_date,
			'request_title' => $item->request_title
                    ];
                }));
            }

            
            $data = [
                //'contract' => $countGenerateNumber > 0 ? $combinedResults :  Document::where('category', 1)->orderBy('created_at', 'desc')->get(),
		'contract' => $countGenerateNumber > 0 
                ? $combinedResults 
                : $documents,

                'duration' => Alert::get()
            ];
		
            return view('master.contract_number.index')->with('data', $data);   
        } catch (Exception $e) {    
            // dd($e);
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
        //
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
}
