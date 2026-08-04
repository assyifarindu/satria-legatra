<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Title;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('master-title') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{
            $data = [
                'title' => Title::where('is_delete', false)->get()
            ];

            return view('master.title.index')->with('data', $data);
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
                'name' => $request->title,
                'code' => $request->code,
                'is_delete' => false,
                'created_by' => Auth::user()->id
            );

            Title::create($data);

            Alert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-title.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function show(Title $title)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function edit(Title $title)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function updateTitle(Request $request, Title $title)
    {
        try{
            $title = Title::findOrFail($request->id);

            $title->name = $request->title;
            $title->code = $request->code;
            $title->update();

            return redirect()->route('master-title.index');

        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $title = Title::findOrFail($id);
            $title->is_delete = true;
            $title->update();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-title.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }
}
