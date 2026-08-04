<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\HakiType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class HakiTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('master-haki-type') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{
            $data = [
                'haki_type' => HakiType::all()
            ];

            return view('master.haki_type.index')->with('data', $data);
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

            HakiType::create($data);

            Alert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-haki-type.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\HakiType  $hakiType
     * @return \Illuminate\Http\Response
     */
    public function show(HakiType $hakiType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\HakiType  $hakiType
     * @return \Illuminate\Http\Response
     */
    public function edit(HakiType $hakiType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\HakiType  $hakiType
     * @return \Illuminate\Http\Response
     */
    public function updateHaki(Request $request)
    {
        try{
            $haki = HakiType::findOrFail($request->id);

            $haki->name = $request->name;
            $haki->description = $request->description;
            $haki->update();

            return redirect()->route('master-haki-type.index');

        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\HakiType  $hakiType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $haki = HakiType::findOrFail($id);
            $haki->delete();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-haki-type.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
