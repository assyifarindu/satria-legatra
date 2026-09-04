<?php

use App\Models\Table\BaseDocument;
use App\Models\Table\BaseDocumentActivity;
use App\Models\Table\Document;
use App\Models\Table\DocumentActivityHistory;
use App\Models\Table\DocumentFinal;
use App\Models\Table\DocumentHistory;
use App\Models\Table\DocumentHistoryAttachment;
use App\Models\Table\DocumentScope;
use App\Models\Table\EmailAlert;
use App\Models\Table\ExtendDocumentActivityHistory;
use App\Models\Table\Feedback;
use App\Models\Table\Negotiation;
use App\Models\Table\Notification;
use App\Models\Table\ParaPihak;
use App\Models\Table\Pic;
use App\Models\Table\PicDocument;
use App\Models\Table\RequestDocument;
use App\Models\Table\RequestDocumentActivity;
use App\Models\Table\RequestDocumentQR;
use App\Models\Table\RequestExisting;
use App\Models\Table\RequestExistingActivity;
use App\Models\Table\Template;
use App\Models\User;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\View\VwPicDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\Table\Company;
use App\Models\Table\TspRequestDocumentCommittees;
use App\Models\UserRoleGroup;

function getTimeAgo($time)
{
    $time_difference = time() - $time;

    if ($time_difference < 1) {
        return 'less than 1 second ago';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;

        if ($d >= 1) {
            $t = round($d);
            return 'about ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
        }
    }
}

function getTimeLater($time)
{
    $time_difference = $time - time();

    if ($time_difference < 1) {
        return 'less than 1 second later';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;

        if ($d >= 1) {
            $t = round($d);
            return 'about ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' later';
        }
    }
}

function formatDate($datetime = null)
{
    $datetime = $datetime ? $datetime : Carbon::now();
    $format   = 'd M Y';
    return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format($format);
}

function formatOnlyDate($date = null)
{
    $date = $date ? $date : Carbon::now();
    $format   = 'd M Y';
    return Carbon::createFromFormat('Y-m-d', $date)->format($format);
}

function getUserName($id)
{
    $user = User::findOrFail($id);
    if ($user) {
        return $user;
    } else {
        return 'Not Found';
    }
}

function getDocumentDrafting($id)
{

    $document = Document::where('request_document_id', $id)->first();
    // if(Auth::user()->id == 178){
    //     dd($id);
    // }
    // dd($document);

    if ($document) {
        return $document;
    } else {
        return 'Not Found';
    }
}

function getDocumentHistory($id)
{
    $document = DocumentHistory::where('document_id', $id)->orderBy('created_at', 'desc')->get();

    if ($document) {
        return $document;
    } else {
        return 'Not Found';
    }
}


function getDocumentHistoryAttachment($id)
{
    $document = DocumentHistoryAttachment::where('document_history_id', $id)->get();

    return $document;
}

function getDocumentFinal($id)
{
    $document = DocumentFinal::where('document_id', $id)->orderBy('created_at', 'desc')->get();

    return $document;
}

function getFeedback($id)
{
    $feedback = Feedback::where('request_document_id', $id)->get();

    if ($feedback) {
        return $feedback;
    } else {
        return 'Not Found';
    }
}


function getExistingDocument($id)
{
    $existing = RequestExistingActivity::where('request_existing_id', $id)->get();

    if ($existing) {
        return $existing;
    } else {
        return 'Not Found';
    }
}

function getLastExistingDocument($id)
{
    $existing = RequestExistingActivity::where('request_existing_id', $id)->orderBy('created_at', 'desc')->first();

    if ($existing) {
        return $existing;
    } else {
        return 'Not Found';
    }
}

function getPihak($id)
{
    $pihak = ParaPihak::where('request_document_id', $id)->get();

    return $pihak;
}

function getNegotiation($id)
{
    $feedback = Negotiation::where('request_document_id', $id)->get();

    if ($feedback) {
        return $feedback;
    } else {
        return 'Not Found';
    }
}

function getDocumentScope($id)
{
    try {
        $department = DocumentScope::where('request_document_id', $id)->get();

        return $department;
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function getDocumentScopeByBase($id)
{
    try {
        $department = DocumentScope::where('base_document_id', $id)->get();

        return $department;
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}
function checkDocumentScope($request_id, $dept)
{
    try {
        $department = DocumentScope::where('department_code', $dept)->where('request_document_id', $request_id)->first();


        if ($department != null) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function checkDocumentScopeByBase($request_id, $dept)
{
    try {
        $department = DocumentScope::where('department_code', $dept)->where('base_document_id', $request_id)->first();


        if ($department != null) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function checkDocumentScopeByDocument($request_id, $dept)
{
    try {
        $document = Document::findOrFail($request_id);
        $department = DocumentScope::where('department_code', $dept)->where('base_document_id', $document->base_document_id)->first();


        if ($department != null) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function getPicDocument($id)
{
    try {
        $pic = VwPicDocument::where('document_id', $id)->get();

        return $pic;
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function checkPicDocumentByBase($request_id, $pic)
{
    try {
        $document = Document::where('base_document_id', $request_id)->where('is_extend', true)->first();
        $pic_document = PicDocument::where('document_id', $document->id)->where('user_id', $pic)->first();


        if ($pic_document != null) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function checkPicDocument($request_id, $pic)
{
    try {
        $pic_document = PicDocument::where('document_id', $request_id)->where('user_id', $pic)->first();

        if ($pic_document != null) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function getDetailEmployee($nrp)
{
    try {

        if (env('IS_OUTSIDE_UTPE')) {
            $division = Http::withHeaders([
                'Authorization' => '39|3UaFeep7tzAATwC4hR84iyiggMdzXP7TGRHBxtM4',
            ])->get('http://satria-apps.patria.co.id/satria-api-man/public/api/sf-emp-detail/' . $nrp);
        } else {
            $division = Http::withHeaders([
                'Authorization' => '39|3UaFeep7tzAATwC4hR84iyiggMdzXP7TGRHBxtM4',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/sf-emp-detail/' . $nrp);
        }

        return $division['data'];
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function getDepartment()
{
    try {
        if (env('IS_OUTSIDE_UTPE')) {
            $department = Http::withHeaders([
                'Authorization' => '39|3UaFeep7tzAATwC4hR84iyiggMdzXP7TGRHBxtM4',
            ])->get('http://satria-apps.patria.co.id/satria-api-man/public/api/sf-dept-list');
        } else {
            $department = Http::withHeaders([
                'Authorization' => '39|3UaFeep7tzAATwC4hR84iyiggMdzXP7TGRHBxtM4',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/sf-dept-list');
        }

        return $department['data'];
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function PermissionActionMenu($menu)
{
    $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link', $menu)->where('app', Auth::user()->accessed_app)->orderBy('id', 'DESC')->first();
    // $appsmenu = VwPermissionAppsMenu::where('user', Auth::user()->id)->where('menu_link',$menu)->orderBy('id', 'DESC')->first();

    if ($appsmenu) {
        if ($appsmenu->access == 1) {
            return true;
        }
        return false;
    }
    return false;
}

function getRomawiMonth($month)
{
    if ($month == '01') {
        $romawi = 'I';
    } elseif ($month == '02') {
        $romawi = 'II';
    } elseif ($month == '03') {
        $romawi = 'III';
    } elseif ($month == '04') {
        $romawi = 'IV';
    } elseif ($month == '05') {
        $romawi = 'V';
    } elseif ($month == '06') {
        $romawi = 'VI';
    } elseif ($month == '07') {
        $romawi = 'VII';
    } elseif ($month == '08') {
        $romawi = 'VIII';
    } elseif ($month == '09') {
        $romawi = 'IX';
    } elseif ($month == '10') {
        $romawi = 'X';
    } elseif ($month == '11') {
        $romawi = 'XI';
    } else {
        $romawi = 'XII';
    }

    return $romawi;
}

function sendEmail($dataEmail, $email)
{

    $to = $email;
    // $to = 'risqi.sultoni@patria.co.id';

    $details = [
        'title' => 'Email Legatra',
        'body' => 'From Legatra',
        'data' => $dataEmail
    ];

    Mail::to($to)->send(new \App\Mail\EmailService($details));
}

function getSlaRequestDocument($id)
{
    // $get_step_one = RequestDocumentActivity::where('step', 1)->where('request_document_id', $id)->first();
    // $get_step_seven = RequestDocumentActivity::where('step', 7)->where('request_document_id', $id)->first();

    // $start = strtotime($get_step_one->created_at);
    // $end = strtotime($get_step_seven->created_at);

    //User Request Submitted
    $get_step_submitted = RequestDocumentActivity::where('step', 0)->where('request_document_id', $id)->first();
    //Legal Drafting
    $get_step_one = RequestDocumentActivity::where('step', 1)->where('request_document_id', $id)->first();

    if (!$get_step_submitted) {
        return 0;
    }

    if (!$get_step_one) {
        return 0;
    }

    $start = strtotime($get_step_submitted->created_at);
    $end = strtotime($get_step_one->created_at);


    $datediff = $end - $start;

    return round($datediff / (60 * 60 * 24));
}

function getSlaRequestDocumentFour($id)
{
    $get_step_one = RequestDocumentActivity::where('step', 1)->where('request_document_id', $id)->first();
    $get_step_seven = RequestDocumentActivity::where('step', 4)->where('request_document_id', $id)->first();

    if (!$get_step_one || !$get_step_seven) {
        return 0; // atau bisa return 0, atau pesan error sesuai kebutuhan
    }

    $start = strtotime($get_step_one->created_at);
    $end = strtotime($get_step_seven->created_at);

    $datediff = $end - $start;

    return round($datediff / (60 * 60 * 24));
}

function countViewDocument($id)
{
    $count_view = DocumentActivityHistory::where('document_id', $id)->count();

    return $count_view;
}

function countViewBaseDocument($id)
{
    $count_view = BaseDocumentActivity::where('base_document_id', $id)->count();

    return $count_view;
}

function countViewExtendDocument($id)
{
    $count_view = ExtendDocumentActivityHistory::where('extended_document_id', $id)->count();

    return $count_view;
}

function addNotification($user_id, $url, $feature, $id_feature)
{
    $data = array(
        'user_id' => $user_id,
        'url' => $url,
        'feature' => $feature,
        'id_feature' => $id_feature,
        'created_by' => Auth::user()->id
    );

    Notification::create($data);
}

function clickedNotification($user_id, $id_feature, $feature)
{
    $notification = Notification::where('user_id', $user_id)->where('id_feature', $id_feature)->where('feature', $feature)->where('is_clicked', false)->first();
    if ($notification != null) {
        $notification->is_clicked = true;
        $notification->update();
    }
}

function getNotification($user_id)
{
    $notification = Notification::where('user_id', $user_id)->where('is_clicked', false)->orderBy('created_at', 'desc')->get();

    return $notification;
}

function countNotification($user_id)
{
    $notification = Notification::where('user_id', $user_id)->where('is_clicked', false)->count();

    return $notification;
}

function deleteNotification($user_id, $link, $feature, $id)
{
    try {
        $notification = Notification::where('user_id', $user_id)->where('url', $link)->where('feature', $feature)->where('id_feature', $id)->get();

        foreach ($notification as $item) {
            $not = Notification::findOrFail($item->id);
            $not->delete();
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function countContract()
{
    $id = Auth::user()->id;
    $user = User::findOrFail($id);
    $company = $user->company_name;

    $contract = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
        ->where('status', '<', 7)->where('type', 'Contract')->where('is_cancel', 0)->where('satria.users.company_name', $company)->count();

    return $contract;
}

function countLicense()
{
    $id = Auth::user()->id;
    $user = User::findOrFail($id);
    $company = $user->company_name;

    $contract = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
        ->where('status', '<', 4)->where('type', 'License')->where('is_cancel', 0)->where('satria.users.company_name', $company)->count();

    return $contract;
}

function countHaki()
{
    $id = Auth::user()->id;
    $user = User::findOrFail($id);
    $company = $user->company_name;

    $contract = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
        ->where('status', '<', 4)->where('type', 'Haki')->where('is_cancel', 0)->where('satria.users.company_name', $company)->count();

    return $contract;
}

function countExisting()
{
    $id = Auth::user()->id;
    $user = User::findOrFail($id);
    $company = $user->company_name;

    $contract = RequestExisting::join('satria.users', 'request_existings.created_by', '=', 'satria.users.id')
        ->where('status', '<', 5)->where('is_deleted', false)->where('is_cancel', false)->where('satria.users.company_name', $company)->count();

    return $contract;
}

function countRequestQR()
{
    $user = User::where('id', Auth::user()->id)->first();
    $company_name = $user->company_name;
    $company = Company::where('name', $company_name)->first();


    $request = RequestDocumentQR::join('satria.users', 'user_id', '=', 'satria.users.id')->where('satria.users.company_name', $company_name)
        ->where('status_action', 'New')->count();

    return $request;
}

function countRequest()
{
    $id = Auth::user()->id;
    $user = User::findOrFail($id);
    $company = $user->company_name;

    // $contract = RequestDocument::where('status', '<', 4)->where('type', 'Haki')->where('is_cancel', 0)->count();
    // $license = RequestDocument::where('status', '<', 4)->where('type', 'License')->where('is_cancel', 0)->count();
    // $haki = RequestDocument::where('status', '<', 7)->where('type', 'Contract')->where('is_cancel', 0)->count();

    $docCount = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
        ->where('request_documents.is_cancel', 0)
        ->where('satria.users.company_name', $company)
        ->where(function ($query) {
            $query->where(function ($q) {
                // Kondisi untuk tipe Haki (misalnya untuk $contract)
                $q->where('request_documents.type', 'Haki')
                    ->where('request_documents.status', '<', 4);
            })
                ->orWhere(function ($q) {
                    // Kondisi untuk tipe License
                    $q->where('request_documents.type', 'License')
                        ->where('request_documents.status', '<', 4);
                })
                ->orWhere(function ($q) {
                    // Kondisi untuk tipe Contract (misalnya untuk $haki)
                    $q->where('request_documents.type', 'Contract')
                        ->where('request_documents.status', '<', 7);
                });
        })
        ->count();

    $existing = RequestExisting::join('satria.users', 'request_existings.created_by', '=', 'satria.users.id')
        ->where('status', '<', 5)->where('is_deleted', false)->where('is_cancel', false)->where('satria.users.company_name', $company)->count();

    // return $contract + $license + $haki + $existing;
    return $docCount + $existing;
}

function countAlert()
{
    $alert = EmailAlert::where('to', Auth::user()->id)->where('is_action', false)->count();

    return $alert;
}

function extendDocumentAutomatically()
{
    try {
        $now = date('Y-m-d');

        $base = BaseDocument::where('end_contract_date', '<=', $now)->where('is_extend_automatically', true)->where('is_unlimited_duration', false)->where('deleted_at', NULL)->get();

        foreach ($base as $key => $value) {
            $document = Document::where('is_extend', 1)->where('base_document_id', $value->id)->first();
            // dd($value->id);

            // add duration
            $new_end_contract_date = date('Y-m-d', strtotime($value->end_contract_date . ' + ' . $value->duration_days . ' days'));
            $new_start_alert_date = date('Y-m-d', strtotime($new_end_contract_date . ' - ' . $value->alert_days . ' days'));


            $data = array(
                'description' => $value->description,
                'priority' => $value->priority,
                'contract_number' => $value->contract_number,
                'category' => $value->category,
                'company_id' => $value->company_id,
                'company' => $value->company,
                'serial_number' => 0,
                'duration' => 0,
                'note' => $value->note,
                'file' => '-',
                'created_by' => Auth::user()->id,
                'is_extend' => 1,
                'status' => 0,
                'request_document_id' => NULL,
                'revision' => 0,
                'title_id' => $value->title_id,
                'document_type' => $value->document_type,
                'letter_type' => $value->letter_type,
                'letter_purpose' => $value->letter_purpose,
                'request_by' => $value->request_by,
                'alert_days' => $value->alert_days,
                'contract_date' => $value->contract_date,
                'deal_date' => $value->deal_date,
                'end_contract_date' => $new_end_contract_date,
                'is_extend_automatically' => $value->is_extend_automatically,
                'is_unlimited_duration' => $value->is_unlimited_duration,
                'duration_days' => $value->duration_days,
                'base_document_id' => $value->id
            );

            $ddd = Document::create($data);

            $new_data = array(
                'start_alert_date' => $new_start_alert_date,
                'end_contract_date' => $new_end_contract_date
            );

            BaseDocument::where('id', $value->id)->update($new_data);

            // update is extend 
            Document::where('id', $document->id)->update(['is_extend' => false]);

            // Email Alert
            $user = User::where('id', $value->last_request_by)->first();

            $dataEmail = array(
                'title' => $value->description,
                'contract_number' => $value->contract_number,
                'end_contract' => $value->end_contract_date,
                'id' => Hashids::encode($value->id),
                'type' => $value->category
            );

            $details = [
                'title' => 'Email Legatra',
                'body' => 'From Legatra',
                'data' => $dataEmail
            ];

            Mail::to($user->email_sf)->send(new \App\Mail\ExtendDocument\ExtendDocumentUser($details));
        }
    } catch (Exception $e) {
        dd($e);
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function sendEmailAutomatically($created_by = null)
{
    try {
        $now = date('Y-m-d');
        if ($created_by == null) {
            $base = BaseDocument::where('start_alert_date', '<=', $now)->where('is_extend_automatically', false)->where('deleted_at', NULL)->orderBy('start_alert_date', 'desc')->get();
        } else {
            $base = BaseDocument::where('start_alert_date', '<=', $now)->where('last_request_by', $created_by)->where('is_extend_automatically', false)->where('deleted_at', NULL)->orderBy('start_alert_date', 'desc')->get();
        }

        foreach ($base as $item) {
            $document = Document::where('is_extend', 1)->where('base_document_id', $item->id)->first();
            $check = EmailAlert::where('base_document_id', $item->id)->where('document_id', $document->id)->count();

            if ($check < 1) {
                $requester = User::findOrFail($item->last_request_by);
                $pic = PicDocument::where('document_id', $document->id)->get();
                $data = array(
                    'base_document_id' => $item->id,
                    'document_id' => $document->id,
                    'to' => $item->last_request_by,
                    'created_by' => Auth::user()->id
                );

                EmailAlert::create($data);

                $dataEmail = array(
                    'title' => $item->description,
                    'contract_number' => $item->contract_number,
                    'end_contract' => date('Y-m-d', strtotime($item->contract_date . ' + ' . $item->duration_days . ' days'))
                );

                addNotification($item->last_request_by, 'alert-user.index', 'Alert Due Date Document', 0);
                sendEmail($dataEmail, $requester->email_sf);

                foreach ($pic as $key => $value) {
                    $pic_document = User::findOrFail($value->user_id);

                    if (checkEmail($pic_document->email_sf)) {
                        $data = array(
                            'base_document_id' => $item->id,
                            'document_id' => $document->id,
                            'to' => $value->user_id,
                            'created_by' => Auth::user()->id
                        );

                        EmailAlert::create($data);

                        addNotification($value->user_id, 'alert-user.index', 'Alert Due Date Document', 0);
                        sendEmail($dataEmail, $pic_document->email_sf);
                    }
                }

                $legal_pic = Pic::where('is_email_notification', true)->get();

                $dataEmail = array(
                    'title' => $item->description,
                    'contract_number' => $item->contract_number,
                    'end_contract' => date('Y-m-d', strtotime($item->contract_date . ' + ' . $item->duration_days . ' days'))
                );

                foreach ($legal_pic as $key => $lp) {
                    $to = $lp->email;
                    $details = [
                        'title' => 'Email Legatra',
                        'body' => 'From Legatra',
                        'data' => $dataEmail
                    ];

                    Mail::to($to)->send(new \App\Mail\EmailToLegal($details));
                }
            }
        }
    } catch (Exception $e) {
        dd($e);
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function getContractType($id)
{
    try {
        $contract = Document::findOrFail($id);

        if ($contract != NULL) {
            if ($contract->document_type == 'Agg') {
                return 'Agreement';
            } else {
                return $contract->letter_type . ' - ' . $contract->letter_purpose;
            }
        } else {
            return '-';
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function checkEmail($email)
{
    $find1 = strpos($email, '@');
    $find2 = strrpos($email, '.');
    return ($find1 !== false && $find2 !== false && $find2 > $find1);
}

function getTemplateByUser()
{
    try {
        $user = User::where('id', Auth::user()->id)->first();
        $companyid = $user->companyid;
        $company  = Company::where('company_id', $companyid)->first();

        return Template::where('company_name', $company->name)->get();
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

function sendEmailPic()
{
    try {
        $user = User::where('id', Auth::user()->id)->first();
        $company_id = $user->companyid;
        $company = Company::where('company_id', $company_id)->first();

        $pic = Pic::where('is_email_notification', true)->where('company_id', $company->id)->get();


        // $pic = Pic::where('is_email_notification', true)->get();

        foreach ($pic as $key => $value) {
            $details = [
                'title' => 'Email Legatra',
                'body' => 'From Legatra',
            ];



            Mail::to($value->email)->send(new \App\Mail\RequestEmailService($details));
            // dd('success');
            // try {
            //     Mail::to($value->email)->send(new RequestEmailService($details));
            //     \Log::info("Email berhasil dikirim ke: " . $value->email);
            // } catch (\Exception $e) {
            //     \Log::error("Gagal mengirim email ke {$value->email}: " . $e->getMessage());
            // }
        }
    } catch (\Exception $e) {
        Log::error("Gagal mengirim email ke {$value->email}: " . $e->getMessage());
    }
}

function sendEmailFeedbacktoPic($data)
{
    $user = User::where('id', Auth::user()->id)->first();
    $company_id = $user->companyid;
    $company = Company::where('company_id', $company_id)->first();



    //PIC yang menerima email notifikasi
    $pic = Pic::where('is_email_notification', true)->where('company_id', $company->id)->get();



    foreach ($pic as $key => $value) {

        $details = [
            'type' => $data['type'],
            'id' => $data['id'],
            'title' => $data['title'],
            'body' => 'From Legatra',
        ];

        // dd('Before send', $value->email);
        Mail::to($value->email)->send(new \App\Mail\FeedbackEmailService($details));
    }
}

function sendEmailNegotiation($data)
{
    $user = User::where('id', Auth::user()->id)->first();
    $company_id = $user->companyid;
    $company = Company::where('company_id', $company_id)->first();



    //PIC yang menerima email notifikasi
    $pic = Pic::where('is_email_notification', true)->where('company_id', $company->id)->get();



    foreach ($pic as $key => $value) {

        $details = [
            'type' => $data['type'],
            'id' => $data['id'],
            'title' => $data['title'],
            'body' => 'From Legatra',
        ];

        // dd('Before send', $value->email);
        Mail::to($value->email)->send(new \App\Mail\ReminderNegotiation\ReminderEmailToLegal($details));
    }
}

function sendReminder($email, $details)
{
    try {
        Mail::to($email)->send(new \App\Mail\ReminderNegotiation\ReminderEmail($details));
    } catch (Exception $e) {
        dd($e);
        return redirect()->back()->with('error', 'Error Request, Exception Error ');
    }
}

/**
 * Get the role name of a user by their ID.
 *
 * @param int $id The ID of the user.
 * @return string|null The name of the role, or null if not found.
 */
function getRoles($id)
{
    $user = UserRoleGroup::where('user', $id)->where('satria.role_group.apps', '=', 31)
        ->leftjoin('satria.role_group', 'satria.user_role_group.group', '=', 'satria.role_group.id')
        ->select('satria.role_group.*')->first();
    if (!$user) {
        return null; // Return null if no user is found
    }
    $role = $user->name;

    return $role;
}

function getAdminLegalTSP()
{
    $adminLegalTSP = UserRoleGroup::where('satria.role_group.apps', '=', 31)
        ->where('satria.role_group.name', 'Admin Legal TSP')
        ->leftjoin('satria.role_group', 'satria.user_role_group.group', '=', 'satria.role_group.id')
        ->leftjoin('satria.users', 'satria.user_role_group.user', '=', 'satria.users.id')
        ->select('satria.users.*')->get();

    return $adminLegalTSP;
}

/**
 * Get the BOD not verified record for a specific committee and request document.
 *
 * @param int $id The ID of the request document.
 * @return TspRequestDocumentCommittees|null The BOD not verified record, or null if not found.
 */
function getBODNotVerified($id)
{
    $bodNotVerified = TspRequestDocumentCommittees::where('committee_id', Auth::user()->id)
        ->where('request_document_id', $id)
        ->where('verification_ld_status', false)
        ->first();

    return $bodNotVerified;
}
