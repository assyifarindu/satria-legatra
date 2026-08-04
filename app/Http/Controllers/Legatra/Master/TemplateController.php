<?php

namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\Company;
use App\Models\Table\Template;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('master-template') == 0) {
                return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try{
            $user = User::where('id', Auth::user()->id)->first();
            $companyid = $user->companyid;
            $company = Company::where('company_id', $companyid)->first();


            $data = [
                'template' => Template::where('company_id', $company->id)->get(),
                // 'company' => Company::all()
                'company' => Company::where('company_id', $companyid)->first()
            ];

            return view('master.template.index')->with('data', $data);
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
            // File
            $request->validate([
                'file' => 'required',
            ]);

            $fileName = 'Template-'.time().'.'.request()->file->getClientOriginalExtension();
            request()->file->move(public_path('upload/master/template'), $fileName);

            $company = Company::findOrFail($request->company);

            $data = array(
                'title' => $request->title,
                'description' => $request->description,
                'file' => $fileName,
                'created_by' => Auth::user()->id,
                'company_id' => $request->company,
                'company_name' => $company->name
            );

            $insert = Template::create($data);

            Alert::success('Data Saved Successfully', 'Success Message');

            return redirect()->route('master-template.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    public function updateTemplate(Request $request, Template $template)
    {
        try{

            $company = Company::findOrFail($request->company);
            
            $template = Template::findOrFail($request->id);

            $template->title = $request->title;
            $template->description = $request->description;
            $template->company_id = $request->company;
            $template->company_name = $company->name;

            if (!empty($request->file)) {
                // delete old picture
                $doc = public_path('upload/master/template/').$template->file;
                unlink($doc);

                // upload new picture
                $request->validate([
                    'file' => 'required',
                ]);

                $fileName = 'Template-'.time().'.'.request()->file->getClientOriginalExtension();
                request()->file->move(public_path('upload/master/template'), $fileName);

                $template->file = $fileName;
            }

            $template->update();

            return redirect()->route('master-template.index');

        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $template = Template::findOrFail($id);
            if ($template->file != null) {
                // delete
                $doc = public_path('upload/master/template/').$template->file;
                unlink($doc);
            }
            $template->delete();

            Alert::success('Data Delete Successfully', 'Success Message');

            return redirect()->route('master-template.index');
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
        }
    }

    public function download($id)
    {
        $template = Template::findOrFail($id);
        $file = "upload/master/template/".$template->file;
    	$filePath = public_path($file);
    	$fileName = $template->file;

    	return response()->download($filePath, $fileName);
    }
}
