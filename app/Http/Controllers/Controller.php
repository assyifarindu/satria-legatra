<?php

namespace App\Http\Controllers;

use App\Models\ErrorLogs;
use App\Models\Table\ErrorLogs as ErrorLogsLegatra;
use App\Models\View\VwPermissionAppsMenu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Exception;

class Controller extends BaseController
{
    public static function MapPublicPath()
    {
        $path = public_path() . '/';
        if (env('DEPLOYMENT_STATUS', 0) == 1) {
            $path = "";
        }
        return $path;
    }
    public static function PermissionMenu($menu)
    {
        $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link', $menu)->where('app', Auth::user()->accessed_app)->count();

        return $appsmenu;
    }

    public static function PermissionActionMenu($menu)
    {
        $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link', $menu)->where('app', Auth::user()->accessed_app)->orderBy('id', 'DESC')->first();

        return $appsmenu;
    }
    public static function CekDetailApi($token, $request)
    {
        // Api-Satria
    }

    public function get_department()
    {
        try {
            $department = Http::withHeaders([
                'Authorization' => '38|rCG1TgCKPV1YMUv5AKP8zi8ukemsvL3L4QMJNDN2',
            ])->get('https://satria-apps.patria.co.id/satria-api-man/public/api/sf-dept-list');


            return $department['data'];
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function get_atasan($nrp)
    {
        try {
            $employee = Http::withHeaders([
                'Authorization' => '38|rCG1TgCKPV1YMUv5AKP8zi8ukemsvL3L4QMJNDN2',
            ])->get('https://satria-apps.patria.co.id/satria-api-man/public/api/sf-emp-atasan/' . $nrp);


            return $employee['data'];
        } catch (Exception $e) {
            $this->ErrorLog($e);
            $this->ErrorLogLegatra($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public static function ErrorLog($e)
    {
        try {
            $message = $e->getMessage();
            $code = $e->getCode();
            $string = $e->__toString();
            $remote_addr = $_SERVER['REMOTE_ADDR'];
            $action = url()->current();
            $create = ErrorLogs::create([
                'remote_addr' => $_SERVER['REMOTE_ADDR'],
                'action' => url()->current(),
                'code' => $code,
                'message' => $message,
                'ex_string' => $string,
                'apps' => Auth::user()->accessed_app,
                'created_by' => Auth::user()->email,
            ]);
        } catch (Exception $e) {
        }
    }

    public static function ErrorLogLegatra($e)
    {
        try {
            $message = $e->getMessage();
            $code = $e->getCode();
            $string = $e->__toString();
            $create = ErrorLogsLegatra::create([
                'remote_addr' => $_SERVER['REMOTE_ADDR'],
                'action' => url()->current(),
                'code' => $code,
                'message' => $message,
                'ex_string' => $string,
                'apps' => Auth::user()->accessed_app,
                'created_by' => Auth::user()->email,
            ]);
        } catch (Exception $e) {
        }
    }
}
