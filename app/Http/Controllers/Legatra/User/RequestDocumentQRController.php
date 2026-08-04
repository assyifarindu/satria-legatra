<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\RequestDocumentQR;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RequestDocumentQRController extends Controller
{
    public function index()
    {
        try {
            $requests = RequestDocumentQR::where('user_id', Auth::user()->id)->get();
            return view('user.qr-document.index', [
                'requests' => $requests
            ]);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                ],
                [
                    'email.required' => 'Email address is required.',
                    'email.email' => 'Invalid email address format.',
                ],
            );
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            if ($request->hasFile('file_upload')) {
                $fileName = time() . '-' . $request->file('file_upload')->getClientOriginalName();
                request()->file('file_upload')->move(public_path('storage/request-document/qr'), $fileName);
                $filePath = 'request-document/qr/' . $fileName;

            }else {
                return redirect()->back()->with('error', 'No file uploaded.');
            }
    
            $data = [
                'email' => $request->input('email'),
                'file' => $filePath,
                'user_id' => Auth::user()->id,
                'status_verification' => 'Unverified',
                'status_action' => 'New',
            ];
    
            $response = RequestDocumentQR::create($data);
    
            if ($response) {
                return redirect()->route('request-qr-user')->with('success', 'Request document successfully created');
            } else {
                return redirect()->route('request-qr-user')->with('error', 'Something went wrong when create data request, try again');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error');
        }
    }

    public function destroy($id)
    {
        try {
            $requestDocumentQR = RequestDocumentQR::findOrFail($id);
            Storage::disk('public')->delete($requestDocumentQR->file);
            $requestDocumentQR->delete();
            return redirect()->route('request-qr-user')->with('success', 'Request document successfully deleted!');

        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error');

        }
    }
}
