<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert;
use App\Models\Table\ExtendedDocument;
use Exception;
use Illuminate\Http\Request;

class HakiExtendedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if ($this->PermissionMenu('extended-haki') == 0) {
    //             return redirect('/')->with('error', 'Access denied!');
    //         }
    //         return $next($request);
    //     });
    // }
    public function index()
    {
        try{
            $data = [
                'contract' => ExtendedDocument::join('documents','documents.id','=','extended_documents.document_id')->where('documents.category', 3)->orderBy('created_at', 'desc')->get(['extended_documents.*']),
                'duration' => Alert::get()
            ];

            return view('main.haki.extend_haki.index')->with('data', $data); 

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
}
