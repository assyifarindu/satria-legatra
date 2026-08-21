<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;
use App\Models\Table\TspRequestDocument;
use App\Models\Table\TspRequestDocumentFile;
use App\Models\Table\TspRequestDocumentPic;
use App\Models\Table\TspRequestDocumentCustomer;
use App\Models\Table\TspRequestDocumentCustomerPic;
use App\Models\Table\TspRequestStatus;
use App\Models\Table\TspRequestDocumentHistory;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

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

    /**
     * Display a listing of the resource.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('tsp.request-document.index');
    }

    /**
     * Get request documents with pagination, search, and sorting.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestDocuments(Request $request)
    {
        try {
            $start = $request->input('start', 0);
            $draw = $request->input('draw', 1);
            $length = $request->input('length', 10);
            $searchValue = $request->input('search.value');

            // 1. Validasi & fallback pengurutan (mencegah error offset/undefined index)
            $order = $request->input('order.0');
            $columnIndex = $order['column'] ?? null;
            $columnName = $request->input("columns.{$columnIndex}.name") ?? 'id';
            $dir = ($order['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

            // 2. Hitung total seluruh record secara efisien (tanpa memuat data ke memori)
            $recordsTotal = TspRequestDocument::count();

            // 3. Inisialisasi Query Base
            $query = TspRequestDocument::leftJoin('satria_legatra.tsp_request_status', 'satria_legatra.tsp_request_documents.status_id', '=', 'satria_legatra.tsp_request_status.id')
                    ->leftJoin('satria_legatra.tsp_request_stages', 'satria_legatra.tsp_request_documents.stage_id', '=', 'satria_legatra.tsp_request_stages.id')
                    ->leftJoin('satria_legatra.tsp_request_substages', 'satria_legatra.tsp_request_documents.substage_id', '=', 'satria_legatra.tsp_request_substages.id')
                    ->leftJoin('satria.users', 'satria_legatra.tsp_request_documents.requester_id', '=', 'satria.users.id')
                    ->select(
                        'satria_legatra.tsp_request_documents.id',
                        'satria_legatra.tsp_request_documents.stage_id',
                        'satria_legatra.tsp_request_documents.substage_id',
                        'satria_legatra.tsp_request_documents.status_id',
                        'satria_legatra.tsp_request_status.status as status',
                        'satria_legatra.tsp_request_documents.document_number',
                        'satria_legatra.tsp_request_documents.title',
                        'satria_legatra.tsp_request_documents.contract_type',
                        'satria.users.name as requester',
                        'satria_legatra.tsp_request_documents.potential_amount',
                        'satria_legatra.tsp_request_documents.sign_status',
                        'satria_legatra.tsp_request_documents.is_project',
                        'satria_legatra.tsp_request_documents.created_at',
                        'satria_legatra.tsp_request_documents.updated_at'
                    );

            // 4. Filtering Search
            if (!empty($searchValue)) {
                $query->where(function($q) use ($searchValue) {
                    $q->where('satria_legatra.tsp_request_documents.document_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('satria_legatra.tsp_request_documents.title', 'like', '%' . $searchValue . '%')
                    ->orWhere('satria_legatra.tsp_request_documents.contract_type', 'like', '%' . $searchValue . '%')
                    ->orWhere('satria.users.name', 'like', '%' . $searchValue . '%');
                });
            }

            // 5. Hitung total record setelah di-filter
            $recordsFiltered = $query->count();

            // 6. Ambil data terpaginasi
            $data = $query->orderBy($columnName, $dir)
                        ->skip($start)
                        ->take($length)
                        ->get();

            // 7. Mapping data untuk response
            $data = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'stage_id' => $item->stage_id,
                    'substage_id' => $item->substage_id,
                    'status_id' => $item->status_id,
                    'status' => $item->status ?? '-',
                    'document_number' => $item->document_number ?? '-',
                    'title' => $item->title ?? '-',
                    'contract_type' => $item->contract_type ?? '-',
                    'requester' => $item->requester ?? '-',
                    'potential_amount' => $item->potential_amount ?? '-',
                    'sign_status' => $item->sign_status ?? '-',
                    'project_category' => $item->is_project == null ? '-' : ($item->is_project ? 'Project' : 'Non-Project')
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

    /**
     * Show the form for creating a new request document.
     * @return \Illuminate\View\View
     */
    public function showCreate()
    {
        return view('tsp.request-document.create');
    }

    /**
     * Get customers based on search query.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers(Request $request)
    {
        try {
            $q = $request->input('q', '');
            $customers = [
               [
                    "id" => 1,
                    "name" => "Customer A",
                    "nib" => "1234567890",
                    "npwp" => "12.345.678.9-012.345",
                    "address" => "Address A",
                    "postal_code" => "12345",
                    "email" => "customerA@example.com"
               ],
               [
                    "id" => 2,
                    "name" => "Customer B",
                    "nib" => "0987654321",
                    "npwp" => "98.765.432.1-098.765",
                    "address" => "Address B",
                    "postal_code" => "54321",
                    "email" => "customerB@example.com"
               ],
               [
                    "id" => 3,
                    "name" => "Customer C",
                    "nib" => "1122334455",
                    "npwp" => "11.223.344.5-112.233",
                    "address" => "Address C",
                    "postal_code" => "67890",
                    "email" => "customerC@example.com"
               ]
            ];

            $customers = collect($customers)->filter(function ($customer) use ($q) {
                if (empty($q)) {
                    return true; // Jika query kosong, tampilkan semua customer
                }
                return stripos($customer['name'], $q) !== false;
            })->values();

            return response()->json([
                "success" => true,
                "message" => "Customers retrieved successfully.",
                "data" => $customers
            ]);
        } catch (Exception $e) {
            return response()->json([
                "success"       => false,
                "error_message" => $e->getMessage(),
                "message"       => "An error has occurred!"
            ], 500);
        }
    }

    /**
     * Get customer by ID
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerById($id)
    {
        try {
            $customers = [
               [
                    "id" => 1,
                    "name" => "Customer A",
                    "nib" => "1234567890",
                    "npwp" => "12.345.678.9-012.345",
                    "address" => "Address A",
                    "postal_code" => "12345",
                    "email" => "customerA@example.com"
               ],
               [
                    "id" => 2,
                    "name" => "Customer B",
                    "nib" => "0987654321",
                    "npwp" => "98.765.432.1-098.765",
                    "address" => "Address B",
                    "postal_code" => "54321",
                    "email" => "customerB@example.com"
               ],
               [
                    "id" => 3,
                    "name" => "Customer C",
                    "nib" => "1122334455",
                    "npwp" => "11.223.344.5-112.233",
                    "address" => "Address C",
                    "postal_code" => "67890",
                    "email" => "customerC@example.com"
               ]
            ];

            $customer = collect($customers)->firstWhere('id', (int)$id);

            if ($customer) {
                return response()->json([
                    "success" => true,
                    "message" => "Customer retrieved successfully.",
                    "data" => $customer
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Customer not found."
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                "success"       => false,
                "error_message" => $e->getMessage(),
                "message"       => "An error has occurred!"
            ], 500);
        }
    }

    /**
     * Store a newly created request document in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function store(Request $request)
    {
        $db = DB::connection('legatra');
        $action = $request->input('action');

        /* VALIDASI ACTION*/

        if (!in_array($action, ['draft', 'submit'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'action' => 'Action tidak valid.'
                ]);
        }


        /* VALIDASI DRAFT*/

        if ($action === 'draft') {

            $rules = [

                'title' => ['required','string','max:255'],
                // Optional fields
                'customer_id' => [
                    'nullable',
                ],

                'customer_name' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'contract_type' => [
                    'nullable',
                    Rule::in([
                        'Part',
                        'Service',
                        'Reman',
                        'Unit'
                    ])
                ],

                'potential_amount' => [
                    'nullable',
                    'numeric'
                ],

                'sign_status' => [
                    'nullable',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'project_category' => [
                    'nullable',
                    'boolean'
                ],

                'sow' => [
                    'nullable',
                    'string'
                ],

                'transaction_procedure' => [
                    'nullable',
                    'string'
                ],

                'kpi' => [
                    'nullable',
                    'string'
                ],

                'customer_pic_name' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'customer_pic_position' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'customer_pic_email' => [
                    'nullable',
                    'email',
                    'max:255'
                ],

                'customer_pic_phone' => [
                    'nullable',
                    'string',
                    'max:50'
                ],

                'pic_name' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'pic_position' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'pic_email' => [
                    'nullable',
                    'email',
                    'max:255'
                ],

                'pic_phone' => [
                    'nullable',
                    'string',
                    'max:50'
                ],

                'draft_contract' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:10240'
                ],

                'quotation' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:10240'
                ],
            ];

        }

        /* VALIDASI SUBMIT
        */

        if ($action === 'submit') {

            $rules = [

                'title' => ['required','string','max:255'],
                'customer_id' => ['required'],
                'customer_name' => ['required','string','max:255'],
                'contract_type' => ['required',Rule::in(['Part','Service','Reman','Unit'])],
                'potential_amount' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'sign_status' => [
                    'required',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'project_category' => [
                    'required',
                    'boolean'
                ],

                'sow' => [
                    'required',
                    'string'
                ],

                'transaction_procedure' => [
                    'required',
                    'string'
                ],

                'kpi' => [
                    'required',
                    'string'
                ],

                'customer_pic_name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'customer_pic_position' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'customer_pic_email' => [
                    'required',
                    'email',
                    'max:255'
                ],

                'customer_pic_phone' => [
                    'required',
                    'string',
                    'max:50'
                ],

                'pic_name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'pic_position' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'pic_email' => [
                    'required',
                    'email',
                    'max:255'
                ],

                'pic_phone' => [
                    'required',
                    'string',
                    'max:50'
                ],

                'draft_contract' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10240'
                ],

                'quotation' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10240'
                ],
            ];
        }


        $validated = $request->validate($rules);


        /* VALIDASI PREFIX FILE */

        if ($request->hasFile('draft_contract')) {

            $draftFileName = $request
                ->file('draft_contract')
                ->getClientOriginalName();

            if (!str_starts_with(
                strtolower($draftFileName),
                'draf_contract_'
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'draft_contract' =>
                            'Nama file Draft Contract harus diawali dengan prefix Draf_Contract_.'
                    ]);
            }
        }


        if ($request->hasFile('quotation')) {

            $quotationFileName = $request
                ->file('quotation')
                ->getClientOriginalName();

            if (!str_starts_with(
                strtolower($quotationFileName),
                'quotation_'
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'quotation' =>
                            'Nama file Quotation harus diawali dengan prefix Quotation_.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        $db->beginTransaction();

        try {

            /* CREATE REQUEST DOCUMENT */

            $requestDocument = TspRequestDocument::create([
                'stage_id' => 1,
                'status_id' => $action === 'draft' ? 1 : 2,
                'title' => $validated['title'],
                'contract_type' => $validated['contract_type'] ?? null,
                'requester_id' => Auth::id(), 
                'potential_amount' => $validated['potential_amount'] ?? null,
                'sign_status' => $validated['sign_status'] ?? null,
                'is_project' =>  $validated['project_category'] ?? null,
                'sow' => $validated['sow'] ?? null,
                'transaction_procedure' => $validated['transaction_procedure'] ?? null,
                'kpi' =>$validated['kpi'] ?? null,   
            ]);

            // CREATE PIC
            if (
                !empty($validated['pic_name'])
            ) {
                TspRequestDocumentPic::create([

                    'request_document_id' => $requestDocument->id,

                    'name' => $validated['pic_name'] ?? null,

                    'position' => $validated['pic_position'] ?? null,

                    'email' => $validated['pic_email'] ?? null,

                    'phone' => $validated['pic_phone'] ?? null,
                ]);
            
            }

            /*  CREATE CUSTOMER */
            if (
                !empty($validated['customer_name'])
            ) {
                $requestDocumentCustomer = TspRequestDocumentCustomer::create([

                    'request_document_id' => $requestDocument->id,
                    'name' => $validated['customer_name'] ?? null,

                    'nib' => $validated['customer_nib'] ?? null,

                    'npwp' => $validated['customer_npwp'] ?? null,

                    'address' => $validated['customer_address'] ?? null,
                    'postal_code' => $validated['customer_postal_code'] ?? null,

                    'email' =>$validated['customer_email'] ?? null,
                ]);
            
            }

            /*  CREATE CUSTOMER PIC */

            if (
                !empty($validated['customer_id']) ||
                !empty($validated['customer_pic_name'])
            ) {

                TspRequestDocumentCustomerPic::create([

                    'request_document_customer_id' => $requestDocumentCustomer->id,
    
                    'name' => $validated['customer_pic_name'] ?? null,

                    'position' => $validated['customer_pic_position'] ?? null,

                    'email' => $validated['customer_pic_email'] ?? null,
                    'phone' => $validated['customer_pic_phone'] ?? null,
                ]);
            }

             /* UPLOAD FILE */

            $draftContractPath = null;
            $quotationPath = null;

            if ($request->hasFile('draft_contract')) {

                $draftContractPath = $request
                    ->file('draft_contract')
                    ->store(
                        'request-documents/draft-contract',
                        'public'
                    );
            }

            if ($request->hasFile('quotation')) {

                $quotationPath = $request
                    ->file('quotation')
                    ->store(
                        'request-documents/quotation',
                        'public'
                    );
            }

            if ($request->hasFile('draft_contract')) {
                TspRequestDocumentFile::create([
                    'request_document_id' => $requestDocument->id,
                    'name' => $request->file('draft_contract')->getClientOriginalName(),
                    'document_type' => 'Draft Contract',
                    'file_path' => $draftContractPath,
                ]);
            }

            if ($request->hasFile('quotation')) {
                TspRequestDocumentFile::create([
                    'request_document_id' => $requestDocument->id,
                    'name' => $request->file('quotation')->getClientOriginalName(),
                    'document_type' => 'Quotation',
                    'file_path' => $quotationPath,
                ]);
            }

            if ($action === 'draft') {
                TspRequestDocumentHistory::create([
                    'request_document_id' => $requestDocument->id,
                    'stage_id' => 1,
                    'status_id' => 1, 
                    'action' => 'Create Draft',
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);
            }else if ($action === 'submit') {
                TspRequestDocumentHistory::create([
                    'request_document_id' => $requestDocument->id,
                    'stage_id' => 1,
                    'status_id' => 2, 
                    'action' => 'Submit',
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);
            }


            $db->commit();


            /* RESPONSE */
            Alert::success('Data Saved Successfully', 'Success Message');
            return redirect()
                ->route('tsp.request-document')
                ->with('success', $action === 'draft' ? 'Request document berhasil disimpan sebagai draft.' : 'Request document berhasil disubmit.');

        } catch (\Throwable $e) {
            dd($e);

            $db->rollBack();

            report($e);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan request document.');
        }
    }
}
