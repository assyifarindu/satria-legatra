<?php

namespace App\Http\Controllers\Legatra\User;

use App\Http\Controllers\Controller;
use App\Models\Table\BaseDocument;
use App\Models\Table\EmailAlert;
use App\Models\Table\Pic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class AlertController extends Controller
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
                'alert' => EmailAlert::where('to', Auth::user()->id)->where('is_action', false)->orderBy('created_at', 'desc')->get(),
            ];

            clickedNotification(Auth::user()->id, 0, 'Alert Due Date Document');

            return view('user.alert.index')->with('data', $data); 

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
            $email = EmailAlert::findOrFail($id['0']);

            $data = array(
                'pic' => Pic::all(),
                'base_document' => BaseDocument::findOrFail($email->base_document_id),
                'email' => $email
            );
            return view('user.alert.create')->with('data', $data);

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
