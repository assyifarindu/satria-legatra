<?php

namespace App\Http\Controllers;

use App\Models\Table\Alert;
use App\Models\Table\BaseDocument;
use App\Models\Table\Document;
use App\Models\Table\EmailAlert;
use App\Models\Table\RequestDocument;
use App\Models\Table\Template;
use App\Models\User;
use App\Models\View\VwPermissionAppsMenu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect as FacadesRedirect;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('token.login');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
       
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            return FacadesRedirect::to($actual_link.'/satria/welcome');
    }


    public function profile()
    {       
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            return FacadesRedirect::to($actual_link.'/satria/profile');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        try{
            $user = User::findOrFail(Auth::user()->id);
            $company_name = $user->company_name;
            $user->accessed_app = 31;
            $user->update();
            
            if ($this->PermissionMenu('contract') == 0) {
                return redirect()->route('home-user.index');
            }else{

                $company = DB::connection('legatra')->table('base_documents')
                            ->select('short_name','companies.name', 'companies.name as document')->join('companies','base_documents.company','=','companies.name')->distinct('base_documents.company')->get();
               
                foreach ($company as $value) {
                    $value->document = DB::connection('legatra')->table('base_documents')->select(DB::raw('count(*) as count_document, category'))->groupBy('category')->orderBy('category', 'asc')->where('company', $value->name)->get();
                }

                
                // $tracking = RequestDocument::join('users', 'request_documents.created_by', '=', 'users.id')->where('request_documents.is_cancel', false)->where('users.company_name', $company_name)->orderBy('request_documents.created_at', 'desc')->get();
              

                $data = [   
                    // 'template' => Template::all(),
                    'template' => Template::where('company_name', $company_name)->get(),
                    // 'tracking' => RequestDocument::where('is_cancel', false)->orderBy('created_at', 'desc')->get(),
                    'tracking' => RequestDocument::join('satria.users', 'created_by', '=', 'satria.users.id')->where('satria.users.company_name', $company_name)
                    ->where('is_cancel', false)->orderBy('request_documents.created_at', 'desc')
                    ->select('request_documents.*')
                    ->get(),
                    'contract' => BaseDocument::where('status', 1)->where('is_proceed', false)->where('company', $company_name)->orderBy('created_at', 'desc')->get(),
                    'duration' => Alert::get(),
                    'document' => $company
                ];

               

                sendEmailAutomatically();
        
                return view('dashboard')->with('data', $data);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            dd($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        } 
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        return FacadesRedirect::to($actual_link.'/satria/welcome');
    }
    
}
