<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert;
use App\Models\Table\BaseDocument;
use App\Models\Table\EmailAlert;
use App\Models\Table\RequestDocument;
use App\Models\Table\Template;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        try{

            $companies = DB::connection('legatra')->table('companies')
                ->select('name', 'short_name')
                ->get();

            $documentData = [];

            foreach ($companies as $company) {
                $companyDocuments = DB::connection('legatra')->table('base_documents')
                    ->select(DB::raw('count(*) as count_document, category'))
                    ->where('company', $company->name)
                    ->groupBy('category')
                    ->pluck('count_document', 'category');

                $documentData[] = [
                    'company' => $company->short_name,
                    'ContractLetter' => $companyDocuments[1] ?? 0,
                    'License' => $companyDocuments[2] ?? 0,
                    'HAKI' => $companyDocuments[3] ?? 0,
                ];
            }

            $data = [
                'template' => Template::where('company_name', Auth::user()->company_name)->get(),
                'tracking' => RequestDocument::where('created_by', Auth::user()->id)->where('is_cancel', false)->orderBy('created_at', 'desc')->get(),
                'contract' => EmailAlert::where('to', Auth::user()->id)->where('is_action', false)->orderBy('created_at', 'desc')->get(),
                'duration' => Alert::get(),
                'documentData' => $documentData
            ];

            sendEmailAutomatically(Auth::user()->id);
    
            return view('user.home.index')->with('data', $data);
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

    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('login');
    }

    public function download($id)
    {
        $template = Template::findOrFail($id);
        $file = "upload/master/template/".$template->file;
    	$filePath = public_path($file);
    	$fileName = $template->file;

    	return response()->download($filePath, $fileName);
    }
}
