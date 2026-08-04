<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert as TableAlert;
use App\Models\Table\BaseDocument;
use App\Models\Table\Document;
use App\Models\Table\EmailAlert;
use App\Models\Table\ExtendedDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('alert-contract') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{

            $user = User::where('id', Auth::user()->id)->first();
            $company_name = $user->company_name;

            $data = [
                'contract' => BaseDocument::where('category', 1)->where('status', 1)->where('is_proceed', false)->where('company', $company_name)->orderBy('created_at', 'desc')->get(),
                'duration' => TableAlert::get()
            ];

            return view('main.alert_contract.index')->with('data', $data); 

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

    public function sendEmail($id)
    {
        try {
            // sendEmail();

            return redirect()->route('contract-alert.index');

        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        } 
    }

    public function historyEmail($id){
        try{
            $doc_id = $id;

            $document = EmailAlert::where('base_document_id', $doc_id)->orderBy('created_at', 'desc')->get();
            $no = 1;
            $response = "<table class='table table-striped'>";
            $response .= "<thead><tr><th>No</th><th>To</th><th>Tanggal</th></tr></thead><tbody>";
            foreach ($document as $item) {
                $response .= "<tr>";
                $response .= "<td>". $no ."</td>";
                $response .= "<td>". getUserName($item->to)->name ." (". getUserName($item->to)->email_sf .")</td>";
                $response .= "<td>". $item->created_at ."</td>";
                $response .= "</tr>";

                $no++;
            }
            $response .= "</tbody></table>";

            return response()->json($response);
        } catch (Exception $e){
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
