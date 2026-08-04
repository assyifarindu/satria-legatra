<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert;
use App\Models\Table\BaseDocument;
use App\Models\Table\BaseDocumentActivity;
use App\Models\Table\Document;
use App\Models\Table\DocumentActivityHistory;
use App\Models\View\VwFiling;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\User;

class HakiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $user = User::findOrFail(Auth::user()->id);
            $company_name = $user->company_name;

            $data = [
                // 'haki' => BaseDocument::where('category', 3)->where('is_extend', 0)->where('status', 1)->where('company', Auth::user()->company_name)->orderBy('created_at', 'desc')->get(),
                'haki' => VwFiling::where('category', 3)->where('status', 1)->where('department_code', Auth::user()->dept)->where('company', $company_name)->orderBy('created_at', 'desc')->get(),
                'duration' => Alert::get()
            ];

            return view('user.haki.index')->with('data', $data);
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
        try {

            $id = Hashids::decode($id);

            $data = [
                'contract' => BaseDocument::findOrFail($id['0'])
            ];

            $dataAvtivity = array(
                'base_document_id' => $id['0'],
                'user_id' => Auth::user()->id,
                'activity_name' => 'Access Preview Document'
            );

            BaseDocumentActivity::create($dataAvtivity);

            return view('user.haki.show-ringkasan')->with('data', $data);
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

            return view('user.haki.detail')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
