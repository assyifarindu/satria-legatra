<?php



namespace App\Http\Controllers\Legatra\Master;

use App\Http\Controllers\Controller;
use App\Models\Table\ErrorLogs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\Hashids;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;



class LogErrorController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('log-error-legatra') == 0) {
                return redirect('/')->with('err_message', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function Index()
    {
        try{
            if($this->PermissionActionMenu('log-error-legatra')->r==1){
                $iduser = Auth::user()->id;
                $data_users = User::where('id', $iduser)->first();

                $errorLogs = ErrorLogs::get()->map(function($item) {
                    $item->ex_string = Str::limit($item->ex_string, 100); // atau Str::words()
                    $item->created_at = Carbon::parse($item->created_at)->format('d-m-Y H:i:s');
                    return $item;
                });


                $data = [
                    'title' => 'Log Error ',
                    'name' => $data_users->name,
                    'error' => $errorLogs,
                ];
                return view('master.log-error.index')->with('data', $data);   
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {  
            // dd($e);  
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }

    // public function insert(Request $request){

    //     try{
    //         if($this->PermissionActionMenu('data-pemateri-idp')->c==1){

    //             $nrp = $request->nrp;

    //             $data_users = User::where('email', $nrp)->orWhere('personal_number', $nrp)->first();

    //             if($data_users == NULL){
    //                 return redirect()->back()->with('err_message', 'user belum masuk di satria !');
    //             }
    //             $id_users = $data_users->id;

                
    //             $cek_users = VwPemateri::where('id_users', $id_users)->where('jenis_pemateri', $request->jenis_pamateri)->count();
    //             if($cek_users > 0){
    //                 return redirect()->back()->with('err_message', 'Data sudah ada dalam tabel!');
    //             }else{
    //                 $data = [
    //                     'id_users' => $id_users,
    //                     'jenis_pemateri' => $request->jenis_pemateri, 
    //                     'created_at' => Carbon::now(),
    //                 ];
    
    //                 Pemateri::insert($data);
    //             }
    //             return redirect()->back()->with('suc_message', 'Data Berhasil disimpan!');
    //         }else{
    //             return redirect()->back()->with('err_message', 'Akses Ditolak!');
    //         }
    //     } catch (Exception $e) {    
    //         $this->ErrorLog($e);
    //         return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
    //     }   
    // }


    // public function update(Request $request){
    //     try{
    //         if($this->PermissionActionMenu('data-pemateri-idp')->u==1){
    //             $id_pemateri = $request->id_pemateri;
            
    //             $nrp = $request->nrp;

    //             $data_users = User::where('email', $nrp)->orWhere('personal_number', $nrp)->first();

    //             if($data_users == NULL){
    //                 return redirect()->back()->with('err_message', 'user belum masuk di satria !');
    //             }
    //             $id_users = $data_users->id;

    //             $cek_users = VwPemateri::where('id_users', $id_users)->where('jenis_pemateri', $request->jenis_pamateri)->count();
    //             if($cek_users > 0){
    //                 return redirect()->back()->with('err_message', 'Data sudah ada dalam tabel!');
    //             }else{
    //                 $data = [
    //                     'jenis_pemateri' => $request->jenis_pemateri, 
    //                     'id_users' => $id_users,
    //                 ];
    
    //                 $update = Pemateri::where('id_pemateri', $id_pemateri)->update($data);
    
    //                 if($update){
    //                     return redirect()->back()->with('suc_message', 'Data Berhasil diupdate!');
    //                 }else{
    //                     return redirect()->back()->with('err_message', 'Data tidak Berhasil diupdate!');
    //                 }
    //             }


             
    //         }else{
    //             return redirect()->back()->with('err_message', 'Akses Ditolak!');
    //         }
    //     } catch (Exception $e) {    
    //         $this->ErrorLog($e);
    //         return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
    //     }
        
    // }

    // public function delete($id_pemateri){
    //     try{
    //         if($this->PermissionActionMenu('data-pemateri-idp')->d==1){
    //             Pemateri::where('id_pemateri', $id_pemateri)->delete();

    //             return redirect()->back()->with('suc_message', 'Data Berhasil dihapus!');
    //         }else{
    //             return redirect()->back()->with('err_message', 'Akses Ditolak!');
    //         }
    //     } catch (Exception $e) {    
    //         $this->ErrorLog($e);
    //         return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
    //     }
          
    // }

  
}


   