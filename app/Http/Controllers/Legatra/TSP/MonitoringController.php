<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;
use App\Models\Table\TspDocument;
use App\Models\Table\TspRequestDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
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
    
    /**
     * Display a listing of active contracts.
     */
    public function showActiveContracts()
    {
        return view('tsp.monitoring.active-contract');
    }


    /**
     * Retrieve active contracts.
     */
    public function getActiveContracts(Request $request)
    {
        try {
            $user = Auth::user();
            $role = getRoles($user->id);
            $start = $request->input('start', 0);
            $draw = $request->input('draw', 1);
            $length = $request->input('length', 10);
            $searchValue = $request->input('search.value');

            //Validasi & fallback pengurutan (mencegah error offset/undefined index)
            $order = $request->input('order.0');
            $columnIndex = $order['column'] ?? null;
            $columnName = $request->input("columns.{$columnIndex}.name") ?? 'id';
            $dir = ($order['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

            // Inisialisasi Query Base
            // $query = TspRequestDocument::leftJoin('satria_legatra.tsp_request_status', 'satria_legatra.tsp_request_documents.status_id', '=', 'satria_legatra.tsp_request_status.id')
            //     ->leftJoin('satria_legatra.tsp_request_stages', 'satria_legatra.tsp_request_documents.stage_id', '=', 'satria_legatra.tsp_request_stages.id')
            //     ->leftJoin('satria_legatra.tsp_request_substages', 'satria_legatra.tsp_request_documents.substage_id', '=', 'satria_legatra.tsp_request_substages.id')
            //     ->leftJoin('satria.users', 'satria_legatra.tsp_request_documents.requester_id', '=', 'satria.users.id')
            //     ->leftJoin('satria_legatra.tsp_request_document_histories', 'satria_legatra.tsp_request_documents.id', '=', 'satria_legatra.tsp_request_document_histories.request_document_id')
            //     ->leftJoin('satria_legatra.tsp_request_document_customers', 'satria_legatra.tsp_request_document_customers.request_document_id', '=', 'satria_legatra.tsp_request_documents.id')
            //     ->leftJoin('satria_legatra.tsp_form_legal_reviews', 'satria_legatra.tsp_form_legal_reviews.request_document_id', '=', 'satria_legatra.tsp_request_documents.id')
            //     ->leftJoin('satria.mst_dept', 'satria.mst_dept.id', '=', 'satria_legatra.tsp_form_legal_reviews.department_id')
            //     ->select(
            //         'satria_legatra.tsp_request_documents.id',
            //         'satria_legatra.tsp_request_documents.document_number',
            //         'satria_legatra.tsp_request_document_customers.name as customer_name',
            //         'satria_legatra.tsp_request_documents.title',
            //         'satria_legatra.tsp_form_legal_reviews.date as document_date',
            //         'satria_legatra.tsp_form_legal_reviews.start_date',
            //         'satria_legatra.tsp_form_legal_reviews.end_date',
            //         'satria.mst_dept.depthead_name'
            //     )
            //     ->where('satria_legatra.tsp_request_documents.stage_id', 11)
            //     ->distinct();


            // if ($role === 'Admin Legal TSP') {
            //     $query->whereNotIn('satria_legatra.tsp_request_documents.status_id', [1, 3]);
            // } else {
            //     $query->where(function ($q) use ($user_id) {
            //         $q->where('satria_legatra.tsp_request_documents.requester_id', $user_id)
            //             ->orWhere('satria_legatra.tsp_request_document_histories.assigned_to', $user_id);
            //     });
            // }

            // $query->orderByRaw("
            //     CASE 
            //         WHEN satria_legatra.tsp_request_documents.status_id IN (3, 4) THEN 2
            //         WHEN satria_legatra.tsp_request_documents.status_id < 18 THEN 0
            //         ELSE 1
            //     END
            // ")
            //     // ->orderBy('satria_legatra.tsp_request_documents.stage_id', 'asc')
            //     ->orderBy('satria_legatra.tsp_request_documents.created_at', 'asc');

            $query = TspDocument::leftJoin('satria.mst_dept', 'satria.mst_dept.id', '=', 'satria_legatra.tsp_documents.department_id')
                ->leftJoin('satria_legatra.tsp_document_customers', 'satria_legatra.tsp_document_customers.document_id', '=', 'satria_legatra.tsp_documents.id')
                ->leftJoin('satria_legatra.tsp_document_committees', 'satria_legatra.tsp_document_committees.document_id', '=', 'satria_legatra.tsp_documents.id')
                ->leftJoin('satria_legatra.tsp_document_request_documents', 'satria_legatra.tsp_document_request_documents.document_id', '=', 'satria_legatra.tsp_documents.id')
                ->select(
                    'satria_legatra.tsp_documents.id',
                    'satria_legatra.tsp_document_request_documents.request_document_id',
                    'satria_legatra.tsp_documents.document_number',
                    'satria_legatra.tsp_document_customers.name as customer_name',
                    'satria_legatra.tsp_documents.title',
                    'satria_legatra.tsp_documents.date as document_date',
                    'satria_legatra.tsp_documents.start_date',
                    'satria_legatra.tsp_documents.end_date',
                    'satria.mst_dept.depthead_name'
                )
                ->distinct();

            if ($role !== 'Admin Legal TSP') {
                $query->where('satria_legatra.tsp_documents.requester_id', $user->id)
                    ->orWhere('satria_legatra.tsp_document_committees.committee_id', $user->id)
                    ->orWhere('satria_legatra.tsp_documents.department_id', $user->dept);
            }

            // Hitung total seluruh record secara efisien (tanpa memuat data ke memori)
            $recordsTotal = count($query->get());

            // Filtering Search
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('satria_legatra.tsp_documents.document_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('satria_legatra.tsp_document_customers.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('satria_legatra.tsp_documents.title', 'like', '%' . $searchValue . '%')
                        ->orWhere('satria_legatra.tsp_documents.date', 'like', '%' . $searchValue . '%')
                        ->orWhere('satria_legatra.tsp_documents.start_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('satria_legatra.tsp_documents.end_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('satria.mst_dept.depthead_name', 'like', '%' . $searchValue . '%');
                });
            }

            // Hitung total record setelah di-filter
            $recordsFiltered = count($query->get());

            // Ambil data terpaginasi
            $data = $query->orderBy($columnName, $dir)
                ->skip($start)
                ->take($length)
                ->get();


            // Mapping data untuk response
            $data = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'request_document_id' => $item->request_document_id,
                    'document_number' => $item->document_number ?? '-',
                    'customer_name' => $item->customer_name ?? '-',
                    'title' => $item->title ?? '-',
                    'document_date' => $item->document_date ?? '-',
                    'start_date' => $item->start_date ?? '-',
                    'end_date' => $item->end_date ?? '-',
                    'depthead_name' => $item->depthead_name ?? '-'
                ];
            });

            return response()->json([
                'draw'            => (int) $draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success"       => false,
                "error_message" => $e->getMessage(),
                "message"       => "An error has occurred!"
            ], 500);
        }
    }

    /** Display the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showActiveContractsDetail($id)
    {
        $document = TspDocument::findOrFail($id);
        return view('tsp.monitoring.active-contract-detail', compact('document'));
    }

    /**
     * Get the details of the specified active contract.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveContractsDetail($id)
    {
        try {

            $documents = TspDocument::leftJoin('satria.users', 'satria_legatra.tsp_documents.requester_id', '=', 'satria.users.id')
                ->select('satria_legatra.tsp_documents.*', 'satria.users.name as requester')
                ->with([
                    'customer',
                    'customer.customerPics',
                    'files',
                    'pics',
                ])->findOrFail($id);


            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $documents,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                "success"       => false,
                "error_message" => $e->getMessage(),
                "message"       => "An error has occurred!"
            ], 500);
        }
    }
}
