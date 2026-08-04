<?php

namespace App\Http\Controllers\Legatra;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert as TableAlert;
use App\Models\Table\BaseDocument;
use App\Models\Table\Document;
use App\Models\Table\EmailAlert;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;

class AlertLicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('alert-license') == 0) {
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
                // 'contract' => Document::where('category', 2)->where('is_extend', 0)->where('status', 1)->orderBy('created_at', 'desc')->get(),
                'contract' => BaseDocument::where('category', 2)->where('status', 1)->where('is_proceed', false)->where('company', $company_name)->orderBy('created_at', 'desc')->get(),
                'duration' => TableAlert::get()
            ];

            return view('main.alert_license.index')->with('data', $data);
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
            $base_document = BaseDocument::findOrFail($id['0']);
            $document = Document::where('is_extend', 1)->where('base_document_id', $id['0'])->first();

            $data = [
                'base' => $base_document,
                'document' => $document,
                'pic' => User::whereNull('role_id')->get(),
                'email' => EmailAlert::where('base_document_id', $id['0'])->where('document_id', $document->id)->get()
            ];

            return view('main.alert_license.create')->with('data', $data);
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
    public function sendEmail(Request $request)
    {
        try {

            foreach ($request->to as $key => $value) {
                $data = array(
                    'base_document_id' => $request->base_document_id,
                    'document_id' => $request->document_id,
                    'to' => $request->to[$key],
                    'created_by' => Auth::user()->id
                );

                EmailAlert::create($data);

                $base_document = BaseDocument::findOrFail($request->base_document_id);

                $dataEmail = array(
                    'title' => $base_document->description,
                    'contract_number' => $base_document->contract_number,
                    'end_contract' => $base_document->end_contract_date
                );

                addNotification($request->to[$key], 'alert-user.index', 'Alert Due Date Document', 0);

                $requester = User::findOrFail($request->to[$key]);

                sendEmail($dataEmail, $requester->email_sf);
            }

            Alert::success('Email Alert Sent Successfully', 'Success Message');
            if ($base_document->category == 1) {
                return redirect()->route('contract-alert.index');
            } elseif ($base_document->category == 2) {
                return redirect()->route('license-alert.index');
            } else {
                return redirect()->route('alert-haki.index');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
