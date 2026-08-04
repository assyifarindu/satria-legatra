<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Company;
use App\Models\Table\Pic;
use App\Models\Table\Template;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;

class PicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('master-pic') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $user = User::where('id', Auth::user()->id)->first();
            $companyid = $user->companyid;
            $company = Company::where('company_id', $companyid)->first();
         
            $data = [
                'pic' => Pic::where('company_id', $company->id)->get(),
            ];

            return view('master.pic.index')->with('data', $data);
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
        try {
            $user = User::where('id', Auth::user()->id)->first();
            $company_name = $user->company_name;

            $data = [
                // 'company' => Company::all(),
                'company' => Company::where('name', $company_name)->first(),
                'user' => User::whereNull('role_id')->where('company_name', $company_name)->get()
            ];

            return view('master.pic.create')->with('data', $data);
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
        try {

            $user = User::findOrFail($request->pic);
            $data = array(
                'user_id' => $request->pic,
                'name' => $user->name,
                'email' => $request->email,
                'company_id' => $request->company
            );

            $insert = Pic::create($data);
            Alert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-pic.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\Pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function show(Pic $pic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\Pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $id = Hashids::decode($id);

            $user = User::where('id', Auth::user()->id)->first();
            $company_name = $user->company_name;

            $data = [
                'pic' => Pic::findOrFail($id['0']),
                // 'company' => Company::all(),
                // 'user' => User::all()
                'company' => Company::where('name', $company_name)->first(),
                'user' => User::whereNull('role_id')->where('company_name', $company_name)->get()

            ];

            return view('master.pic.edit')->with('data', $data);
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\Pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $pic = Pic::findOrFail($id);
            $user = User::findOrFail($request->pic);

            $pic->name = $user->name;
            $pic->email = $request->email;
            $pic->company_id = $request->company;
            $pic->user_id = $request->pic;

            $pic->update();

            return redirect()->route('master-pic.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\Pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id = Hashids::decode($id);

            $pic = Pic::findOrFail($id['0']);
            $pic->delete();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-pic.index');
        } catch (Exception $e) {
            dd($e);
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function updateEmail(Request $request)
    {
        try {
            $pic = Pic::findOrFail($request->id);
            $pic->is_email_notification = true;
            $pic->update();
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function updateEmailFalse(Request $request)
    {
        try {
            $pic = Pic::findOrFail($request->id);
            $pic->is_email_notification = false;
            $pic->update();
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
