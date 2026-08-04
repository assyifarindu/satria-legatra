<?php

namespace App\Http\Controllers\Legatra;

use App\Helpers\MyHelper;
use App\Http\Controllers\Controller;
use App\Models\Table\RequestDocumentQR;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class RequestDocumentQRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $requests = RequestDocumentQR::all();
            return view('main.qr-document.index-request', [
                'requests' => $requests,
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()
                ->back()
                ->with('error', 'Error Request, Exception Error' . $e);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequestDocumentQR  $requestDocumentQR
     * @return \Illuminate\Http\Response
     */
    public function show(RequestDocumentQR $requestDocumentQR, $id)
    {
        try {
            $id = Hashids::decode($id);
            $request = RequestDocumentQR::findOrFail($id[0]);
            return view('main.qr-document.show-request', [
                'request' => $request
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()
                ->back()
                ->with('error', 'Error Request, Exception Error' . $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequestDocumentQR  $requestDocumentQR
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestDocumentQR $requestDocumentQR, $id)
    {
        try {
            $id = Hashids::decode($id);
            $request = RequestDocumentQR::findOrFail($id[0]);
            $request->update([
                'status_action' => 'Read',
            ]);
            return view('main.qr-document.edit-request', [
                'request' => $request,
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()
                ->back()
                ->with('error', 'Error Request, Exception Error' . $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestDocumentQR  $requestDocumentQR
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestDocumentQR $requestDocumentQR, $id)
    {
        try {
            $requestQR = RequestDocumentQR::findOrFail($id);
            $validasi = Validator::make($request->all(), [
                'status_verification' => 'string|required',
            ]);

            if ($validasi->fails()) {
                return redirect()->route('request-qr.edit')->withErrors($validasi)->withInput();
            }

            if ($request->input('status_verification') == 'Verified') {
                $date_verification = now();
                $user_id = Auth::user()->id;
            } else {
                $date_verification = null;
                $user_id = null;
            }

            $update = [
                'status_verification' => $request->input('status_verification'),
                'date_verification' => $date_verification,
                'user_verified_id' => $user_id,
            ];

            if ($request->input('status_verification') == 'Verified') {
                MyHelper::sendEmailResult($requestQR->email, $requestQR);
            }

            $requestQR->update($update);

            return redirect()->route('request-qr.index')->with('success', 'Document request validation successfully updated');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequestDocumentQR  $requestDocumentQR
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestDocumentQR $requestDocumentQR)
    {
        //
    }
}
