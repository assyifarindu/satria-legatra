<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('master-company') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{
            $data = [
                'company' => Company::all()
            ];

            return view('master.company.index')->with('data', $data);   
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
                'short_name' => $request->short_name,
            );

            $insert = Company::create($data);

            Alert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-company.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    public function updateCompany(Request $request)
    {
        try{
            $company = Company::findOrFail($request->id);
            $company->name = $request->name;
            $company->short_name = $request->short_name;
            $company->update();

            Alert::success('Data Update Successfully', 'Success Message');
            return redirect()->route('master-company.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $company = Company::findOrFail($id);
            $company->delete();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-company.index');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
