<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Duty;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DutyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('master-duty') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{
            $data = [
                'duty' => Duty::all()
            ];

            return view('master.duty.index')->with('data', $data);
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
        try{

            $data = array(
                'name' => $request->name,
                'description' => $request->description,
            );

            Duty::create($data);

            Alert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-duty.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function show(Duty $duty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function edit(Duty $duty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function updateDuty(Request $request)
    {
        try{
            $duty = Duty::findOrFail($request->id);

            $duty->name = $request->name;
            $duty->description = $request->description;
            $duty->update();

            return redirect()->route('master-duty.index');

        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\Duty  $duty
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $duty = Duty::findOrFail($id);
            $duty->delete();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-duty.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
