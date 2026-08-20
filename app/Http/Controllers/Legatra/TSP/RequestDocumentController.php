<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;

use App\Models\Table\TspRequestDocument;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ServerSideDataTable;


class RequestDocumentController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != NULL) {
                // return redirect('/')->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

   use ServerSideDataTable;

   public function index(){
        return view('tsp.request_document.index');
    }

    

    public function getData(Request $request)
    {
        $query = TspRequestDocument::query()

            ->leftJoin(
                'tsp_request_status',
                'tsp_request_status.id',
                '=',
                'tsp_request_documents.status_id'
            )

            ->leftJoin(
                'users',
                'users.id',
                '=',
                'tsp_request_documents.requester_id'
            )

            ->select(

                'tsp_request_documents.*',

                'tsp_request_status.status',

                'users.name as requester'

            );

        if (Auth::user()->role_id != 1) {

            $query->where(
                'tsp_request_documents.requester_id',
                Auth::id()
            );

        }

        return $this->datatable(

            $request,

            $query,

            [
                0 => 'tsp_request_status.status',

                1 => 'tsp_request_documents.document_number',

                2 => 'tsp_request_documents.title',

                3 => 'tsp_request_documents.contract_type',

                4 => 'users.name',

                5 => 'tsp_request_documents.sign_status'
            ],

            function ($row) {

                return [
                    'status' => $row->status,
                    'document_number' => $row->document_number,
                    'title' => $row->title,
                    'contract_type' => $row->contract_type,
                    'requester' => $row->requester,
                    'sign_status' => $row->sign_status,
                    'project_category' => $row->is_project
                        ? 'Project'
                        : 'Non Project',
                    'action' => view(
                        'tsp.request_document.action',
                        compact('row')
                    )->render()

                ];

            }

        );
    }

}
