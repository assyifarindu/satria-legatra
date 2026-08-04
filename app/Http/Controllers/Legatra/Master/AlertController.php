<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Alert;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use RealRashid\SweetAlert\Facades\Alert as SweetAlert;

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
            if ($this->PermissionMenu('master-alert') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try{
            $data = [
                'alert' => Alert::all()
            ];

            return view('master.alert.index')->with('data', $data);   
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
        try{
            $data = [
            ];

            return view('master.type.create')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $data = array(
                'note' => $request->note,
                'start_alert' => $request->start_alert,
                'duration' => $request->duration,
                'created_by' => Auth::user()->id
            );

            $insert = Alert::create($data);

            SweetAlert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-alert.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\type  $type
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $id = Hashids::decode($id);

            $data = [
                'alert' => Alert::findOrFail($id['0'])
            ];
            
            return view('master.type.edit')->with('data', $data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    public function updateAlert(Request $request)
    {
        try{
            $alert = Alert::findOrFail($request->id);
            $alert->note = $request->note;
            $alert->start_alert = $request->start_alert;
            $alert->duration = $request->duration;
            $alert->update();

            SweetAlert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('master-alert.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $alert = Alert::findOrFail($id);
            $alert->delete();

            SweetAlert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-alert.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
