<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert;
use App\Models\Table\ExtendDocumentActivityHistory;
use App\Models\Table\ExtendedDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class ExtendLicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = [
                'license' => ExtendedDocument::join('documents','documents.id','=','extended_documents.document_id')->where('documents.category', 2)->orderBy('created_at', 'desc')->get(['extended_documents.*']),
                'duration' => Alert::get()
            ];

            return view('user.license.extend')->with('data', $data); 

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
        try{

            $id = Hashids::decode($id);
            $extend_contract = ExtendedDocument::findOrFail($id['0']);

            $data = [
                'contract' => ExtendedDocument::findOrFail($id['0']),
                'contract_history' => ExtendedDocument::where('document_id', $extend_contract->document_id)->orderBy('created_at', 'desc')->get()
            ];

            return view('user.license.show-extend')->with('data', $data); 

        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function showRingkasan($id)
    {
        try{

            $id = Hashids::decode($id);

            $data = [
                'contract' => ExtendedDocument::findOrFail($id['0'])
            ];

            $dataAvtivity = array(
                'extended_document_id' => $id['0'],
                'user_id' => Auth::user()->id,
                'activity_name' => 'Access Preview Document'
            );

            ExtendDocumentActivityHistory::create($dataAvtivity);

            return view('user.license.show-ringkasan')->with('data', $data); 

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
}
