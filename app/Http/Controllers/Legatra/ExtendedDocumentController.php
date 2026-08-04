<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert as TableAlert;
use App\Models\Table\ExtendDocumentActivityHistory;
use App\Models\Table\ExtendedDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;

class ExtendedDocumentController extends Controller
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
                'contract' => ExtendedDocument::join('documents','documents.id','=','extended_documents.document_id')->where('documents.category', 1)->orderBy('created_at', 'desc')->get(['extended_documents.*']),
                'duration' => TableAlert::get()
            ];

            return view('main.contract_extend.index')->with('data', $data); 

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
     * @param  \App\Models\Table\ExtendedDocument  $extendedDocument
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

            return view('main.contract_extend.show')->with('data', $data); 

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

            return view('main.contract_extend.show-ringkasan')->with('data', $data); 

        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        } 
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\ExtendedDocument  $extendedDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtendedDocument $extendedDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\ExtendedDocument  $extendedDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtendedDocument $extendedDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\ExtendedDocument  $extendedDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{ 
            $id = Hashids::decode($id);
            $document = ExtendedDocument::findOrFail($id['0']);
            $document->status = 0;
            $document->update();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('contract.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function viewerExtendDocument($id){
        try{
            $doc_id = $id;

            $document = ExtendDocumentActivityHistory::where('extended_document_id', $doc_id)->orderBy('created_at', 'desc')->get();
            $no = 1;
            $response = "<table class='table table-striped'>";
            $response .= "<thead><tr><th>No</th><th>Viewer</th><th>Tanggal</th></tr></thead><tbody>";
            foreach ($document as $item) {
                $response .= "<tr>";
                $response .= "<td>". $no ."</td>";
                $response .= "<td>". getUserName($item->user_id)->name ."</td>";
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
