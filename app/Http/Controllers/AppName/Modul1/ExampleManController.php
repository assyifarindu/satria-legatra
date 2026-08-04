<?php

namespace App\Http\Controllers\AppName\Modul1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Exception;

class ExampleManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('example-management') == 0){
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
                return $next($request);
            });
    }

    public function AppsTokenMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('example-management')->r==1){
                // Code
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }
    public function AppsTokenMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('example-management')->c==1){
                //code
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }

    public function testInsert(Request $request){
        echo json_encode($request->input());
    }

    public function AppsTokenMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('example-management')->u==1){
                //code
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
   
    public function AppsTokenMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('example-management')->d==1){
                //code
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function AppsTokenMgmtUnDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('example-management')->d==1){
                //code
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
}
