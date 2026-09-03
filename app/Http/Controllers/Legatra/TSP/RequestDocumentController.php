<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;
use App\Models\Table\TspRequestDocument;
use App\Models\Table\TspRequestDocumentCommittees;
use App\Models\Table\TspRequestDocumentFile;
use App\Models\Table\TspRequestDocumentPic;
use App\Models\Table\TspRequestDocumentCustomer;
use App\Models\Table\TspRequestDocumentCustomerPic;
use App\Models\Table\TspRequestDocumentFeedback;
use App\Models\Table\TspRequestDocumentHistory;
use App\Models\Table\TspRequestDocumentFeedbackFile;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;



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
            $user_id = Auth::id();
            $division = Auth::user()->division;
            $superior = Auth::user()->superior;
            $is_bod = $division === 'Board Of Directors' && $superior === null;
            $role = getRoles($user_id);
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
                ->leftJoin('satria_legatra.tsp_request_document_histories', 'satria_legatra.tsp_request_documents.id', '=', 'satria_legatra.tsp_request_document_histories.request_document_id')
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
                )
                ->distinct();

            if ($role === 'Admin Legal TSP') {
                $query->whereNotIn('satria_legatra.tsp_request_documents.status_id', [1, 3]);
            } else if ($is_bod) {
                $query->where('satria_legatra.tsp_request_document_histories.assigned_to', $user_id);
            } else {
                $query->where('satria_legatra.tsp_request_documents.requester_id', $user_id);
            }

            // 4. Filtering Search
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
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
                    'project_category' => $item->is_project === null
                        ? '-'
                        : ((bool) $item->is_project ? 'Project' : 'Non-Project')
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
        $action = $request->input('action_type');
        $rules = [];

        /* VALIDASI DRAFT*/

        if ($action === 'draft') {

            $rules = [

                'title' => ['required', 'string', 'max:255'],
                // Optional fields
                'contract_type' => [
                    'nullable',
                    Rule::in([
                        'Part',
                        'Service',
                        'Reman',
                        'Unit'
                    ])
                ],

                'potential_amount' => ['nullable', 'numeric'],

                'sign_status' => [
                    'nullable',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'is_project' => ['nullable', 'boolean'],

                'sow' => ['nullable', 'string'],
                'transaction_procedure' => ['nullable', 'string'],
                'kpi' => ['nullable', 'string'],

                'pic_name' => ['nullable', 'string', 'max:255'],

                'pic_position' => ['nullable', 'string', 'max:255'],


                'pic_email' => ['nullable', 'email', 'max:255'],

                'pic_phone' => ['nullable', 'string', 'max:50'],
                'draft_contract' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

                'quotation' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

                'other' => ['nullable', 'array'],
                'other.*' => ['file', 'mimes:pdf', 'max:10240'],

                'customer_id' => ['nullable'],

                'customer_name' => ['nullable', 'string', 'max:255'],
                'customer_nib' => ['nullable', 'string', 'max:255'],
                'customer_npwp' => ['nullable', 'string', 'max:255'],
                'customer_address' => ['nullable', 'string', 'max:255'],
                'customer_postal_code' => ['nullable', 'string', 'max:10'],
                'customer_email' => ['nullable', 'email', 'max:255'],
                'customer_pic_name' => ['nullable', 'string', 'max:255'],
                'customer_pic_position' => ['nullable', 'string', 'max:255'],
                'customer_pic_email' => ['nullable', 'email', 'max:255'],
                'customer_pic_phone' => ['nullable', 'string', 'max:50'],
            ];
        }

        /* VALIDASI SUBMIT
        */

        if ($action === 'submit') {

            $rules = [

                'title' => ['required', 'string', 'max:255'],
                'contract_type' => ['required', Rule::in(['Part', 'Service', 'Reman', 'Unit'])],
                'potential_amount' => ['required', 'numeric', 'min:0'],
                'sign_status' => [
                    'required',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'is_project' => ['required', 'boolean'],

                'sow' => ['required', 'string'],

                'transaction_procedure' => ['required', 'string'],

                'kpi' => ['required', 'string'],

                'pic_name' => ['required', 'string', 'max:255'],


                'pic_position' => ['required', 'string', 'max:255'],

                'pic_email' => ['required', 'email', 'max:255'],


                'pic_phone' => ['required', 'string', 'max:50'],

                'draft_contract' => ['required', 'file', 'mimes:pdf', 'max:10240'],

                'quotation' => ['required', 'file', 'mimes:pdf', 'max:10240'],
                'other' => ['nullable', 'array'],
                'other.*' => ['file', 'mimes:pdf', 'max:10240'],

                'customer_id' => ['required'],
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_nib' => ['required', 'string', 'max:255'],
                'customer_npwp' => ['required', 'string', 'max:255'],
                'customer_address' => ['required', 'string', 'max:255'],
                'customer_postal_code' => ['required', 'string', 'max:10'],

                'customer_email' => ['required', 'email', 'max:255'],
                'customer_pic_name' => ['required', 'string', 'max:255'],
                'customer_pic_position' => ['required', 'string', 'max:255'],

                'customer_pic_email' => ['required', 'email', 'max:255'],

                'customer_pic_phone' => ['required', 'string', 'max:50'],


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
                'draft_contract_'
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'draft_contract' =>
                        'Nama file Draft Contract harus diawali dengan prefix Draft_Contract_.'
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


        /*DATABASE TRANSACTION*/

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
                'is_project' =>  $validated['is_project'] ?? null,
                'sow' => $validated['sow'] ?? null,
                'transaction_procedure' => $validated['transaction_procedure'] ?? null,
                'kpi' => $validated['kpi'] ?? null,
            ]);

            // CREATE PIC
            if (
                !empty($validated['pic_name'])
            ) {
                $pic = TspRequestDocumentPic::create([

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

                    'email' => $validated['customer_email'] ?? null,
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

                $file = $request->file('draft_contract');

                // Ambil nama file tanpa extension
                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                // Buat nama file baru
                $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();

                // Simpan langsung ke public/upload/request_document
                $file->move(public_path('upload/request_document'), $fileName);

                // Path yang disimpan ke database
                $draftContractPath = 'upload/request_document/' . $fileName;

                // Simpan record ke database
                TspRequestDocumentFile::create([
                    'request_document_id' => $requestDocument->id,
                    'name' => $fileName,
                    'document_type' => 'Draft Contract',
                    'file_path' => $draftContractPath,
                    'created_by' => Auth::id(),
                ]);
            }

            if ($request->hasFile('quotation')) {

                $quotationPath = $request
                    ->file('quotation')
                    ->store(
                        'request-documents/quotation',
                        'public'
                    );
            }


            if ($request->hasFile('quotation')) {

                $file = $request->file('quotation');

                // Ambil nama file tanpa extension
                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                // Buat nama file baru
                $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();

                // Simpan langsung ke public/upload/request_document
                $file->move(
                    public_path('upload/request_document'),
                    $fileName
                );

                // Path yang disimpan ke database
                $quotationPath =
                    'upload/request_document/' . $fileName;

                // Simpan record ke database
                TspRequestDocumentFile::create([
                    'request_document_id' => $requestDocument->id,
                    'name' => $fileName,
                    'document_type' => 'Quotation',
                    'file_path' => $quotationPath,
                    'created_by' => Auth::id(),
                ]);
            }

            if ($request->hasFile('other')) {
                foreach ($request->file('other') as $file) {
                    // Ambil nama file tanpa extension
                    $name = pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    );

                    // Buat nama file baru
                    $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();

                    // Simpan langsung ke public/upload/request_document
                    $file->move(
                        public_path('upload/request_document'),
                        $fileName
                    );

                    // Path yang disimpan ke database
                    $otherPath =
                        'upload/request_document/' . $fileName;

                    // Simpan record ke database
                    TspRequestDocumentFile::create([
                        'request_document_id' => $requestDocument->id,
                        'name' => $fileName,
                        'document_type' => 'Other',
                        'file_path' => $otherPath,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            if ($action === 'draft') {
                TspRequestDocumentHistory::create([
                    'request_document_id' => $requestDocument->id,
                    'stage_id' => 1,
                    'status_id' => 1,
                    'action' => 'Create Draft',
                    'action_by' => Auth::id(),
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);
            } else if ($action === 'submit') {
                TspRequestDocumentHistory::create([
                    'request_document_id' => $requestDocument->id,
                    'stage_id' => 1,
                    'status_id' => 2,
                    'action' => 'Submit',
                    'action_by' => Auth::id(),
                    'assigned_to' => getAdminLegalTSP()->first()->id ?? null,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);

                /* SEND EMAIL NOTIFICATION TO ADMIN */
                $detail_email = array(
                    'title' => $requestDocument->title,
                    'subject' => 'Request Document',
                    'message' => 'Email Pemberitahuan, ada request document baru yang perlu ditindaklanjuti.',

                );

                Mail::to(getAdminLegalTSP()->first()->email_sf ?? null)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));
            }


            /* RESPONSE */
            Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
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

    /** Show the form for editing the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showEdit($id)
    {
        try {

            $requestDocument = TspRequestDocument::with([
                'customer',
                'files',
            ])->findOrFail($id);

            $draftContract = $requestDocument->files
                ->where('document_type', 'Draft Contract')
                ->first();

            $quotation = $requestDocument->files
                ->where('document_type', 'Quotation')
                ->first();

            $other = $requestDocument->files
                ->where('document_type', 'Other')
                ->all();

            return view(
                'tsp.request-document.edit',
                compact(
                    'requestDocument',
                    'draftContract',
                    'quotation',
                    'other'
                )
            );
        } catch (\Throwable $e) {
            dd($e);
            return redirect()->route('tsp.request-document')
                ->with('error', 'Data Request Document tidak ditemukan.');
        }
    }


    /** Edit the specified request document.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $action = $request->input('action_type');
        $requestDocument = TspRequestDocument::findOrFail($id);
        $previousStatusId = $requestDocument->status_id;
        $draftContract = TspRequestDocumentFile::where('request_document_id', $requestDocument->id)
            ->where(
                'document_type',
                'Draft Contract'
            )
            ->first();
        $quotation = TspRequestDocumentFile::where('request_document_id', $requestDocument->id)
            ->where(
                'document_type',
                'Quotation'
            )
            ->first();

        /*VALIDATION */

        if ($action === 'draft') {

            $rules = [

                'title' => ['required', 'string', 'max:255'],
                // Optional fields
                'contract_type' => [
                    'nullable',
                    Rule::in([
                        'Part',
                        'Service',
                        'Reman',
                        'Unit'
                    ])
                ],

                'potential_amount' => ['nullable', 'numeric'],

                'sign_status' => [
                    'nullable',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'is_project' => ['nullable', 'boolean'],

                'sow' => ['nullable', 'string'],
                'transaction_procedure' => ['nullable', 'string'],
                'kpi' => ['nullable', 'string'],

                'pic_name' => ['nullable', 'string', 'max:255'],

                'pic_position' => ['nullable', 'string', 'max:255'],


                'pic_email' => ['nullable', 'email', 'max:255'],

                'pic_phone' => ['nullable', 'string', 'max:50'],
                'draft_contract' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

                'quotation' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

                'other' => ['nullable', 'array'],
                'other.*' => ['file', 'mimes:pdf', 'max:10240'],

                'customer_id' => ['nullable'],

                'customer_name' => ['nullable', 'string', 'max:255'],
                'customer_nib' => ['nullable', 'string', 'max:255'],
                'customer_npwp' => ['nullable', 'string', 'max:255'],
                'customer_address' => ['nullable', 'string', 'max:255'],
                'customer_postal_code' => ['nullable', 'string', 'max:10'],
                'customer_email' => ['nullable', 'email', 'max:255'],
                'customer_pic_name' => ['nullable', 'string', 'max:255'],
                'customer_pic_position' => ['nullable', 'string', 'max:255'],
                'customer_pic_email' => ['nullable', 'email', 'max:255'],
                'customer_pic_phone' => ['nullable', 'string', 'max:50'],
            ];
        } else {

            $rules = [

                'title' => ['required', 'string', 'max:255'],
                'contract_type' => ['required', Rule::in(['Part', 'Service', 'Reman', 'Unit'])],
                'potential_amount' => ['required', 'numeric', 'min:0'],
                'sign_status' => [
                    'required',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'is_project' => ['required', 'boolean'],

                'sow' => ['required', 'string'],

                'transaction_procedure' => ['required', 'string'],

                'kpi' => ['required', 'string'],

                'pic_name' => ['required', 'string', 'max:255'],


                'pic_position' => ['required', 'string', 'max:255'],

                'pic_email' => ['required', 'email', 'max:255'],


                'pic_phone' => ['required', 'string', 'max:50'],

                'draft_contract' => [($requestDocument->status_id == 1 && !$draftContract) ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],

                'quotation' => [($requestDocument->status_id == 1 && !$quotation) ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],

                'other' => ['nullable', 'array'],
                'other.*' => ['file', 'mimes:pdf', 'max:10240'],

                'customer_id' => ['required'],
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_nib' => ['required', 'string', 'max:255'],
                'customer_npwp' => ['required', 'string', 'max:255'],
                'customer_address' => ['required', 'string', 'max:255'],
                'customer_postal_code' => ['required', 'string', 'max:10'],

                'customer_email' => ['required', 'email', 'max:255'],
                'customer_pic_name' => ['required', 'string', 'max:255'],
                'customer_pic_position' => ['required', 'string', 'max:255'],

                'customer_pic_email' => ['required', 'email', 'max:255'],

                'customer_pic_phone' => ['required', 'string', 'max:50'],


            ];
        }

        $validated = $request->validate($rules);


        /*VALIDASI PREFIX FILE*/

        if ($request->hasFile('draft_contract')) {

            $fileName = strtolower(
                $request
                    ->file('draft_contract')
                    ->getClientOriginalName()
            );

            if (!str_starts_with(
                $fileName,
                'draft_contract_'
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'draft_contract' =>
                        'Nama file Draft Contract harus diawali dengan prefix Draft_Contract_.'
                    ]);
            }
        }

        if ($request->hasFile('quotation')) {

            $fileName = strtolower(
                $request
                    ->file('quotation')
                    ->getClientOriginalName()
            );

            if (!str_starts_with(
                $fileName,
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


        /*UPDATE*/

        $db = DB::connection('legatra');

        $db->beginTransaction();
        try {

            $requestDocument = TspRequestDocument::findOrFail($id);

            /* UPDATE REQUEST DOCUMENT */

            $requestDocument->update([
                'stage_id' => 1,
                'status_id' => $action === 'draft' ? 1 : 2,

                'title' => $validated['title'],

                'contract_type' => $validated['contract_type']
                    ?? $requestDocument->contract_type,

                'potential_amount' => $validated['potential_amount']
                    ?? $requestDocument->potential_amount,

                'sign_status' => $validated['sign_status']
                    ?? $requestDocument->sign_status,

                'is_project' => (bool) $validated['is_project']
                    ?? $requestDocument->is_project,

                'sow' => $validated['sow']
                    ?? $requestDocument->sow,

                'transaction_procedure' =>
                $validated['transaction_procedure']
                    ?? $requestDocument->transaction_procedure,

                'kpi' => $validated['kpi']
                    ?? $requestDocument->kpi,


            ]);


            // UPDATE PIC
            if (
                !empty($validated['pic_name'])
            ) {
                $pic = TspRequestDocumentPic::updateOrCreate(
                    [
                        'request_document_id' => $requestDocument->id,
                    ],
                    [
                        'name' => $validated['pic_name'] ?? null,

                        'position' => $validated['pic_position'] ?? null,

                        'email' => $validated['pic_email'] ?? null,

                        'phone' => $validated['pic_phone'] ?? null,
                    ]
                );
            }



            /*  UPDATE CUSTOMER */
            if (
                !empty($validated['customer_name'])
            ) {
                $customer = TspRequestDocumentCustomer::updateOrCreate(
                    [
                        'request_document_id' => $requestDocument->id,
                    ],
                    [
                        'name' => $validated['customer_name'] ?? null,

                        'nib' => $validated['customer_nib'] ?? null,

                        'npwp' => $validated['customer_npwp'] ?? null,

                        'address' => $validated['customer_address'] ?? null,
                        'postal_code' => $validated['customer_postal_code'] ?? null,

                        'email' => $validated['customer_email'] ?? null,
                    ]
                );
            }


            /*  UPDATE CUSTOMER PIC */
            if (
                !empty($validated['customer_id']) ||
                !empty($validated['customer_pic_name'])
            ) {
                TspRequestDocumentCustomerPic::updateOrCreate(
                    [
                        'request_document_customer_id' => $customer->id,
                    ],
                    [
                        'name' => $validated['customer_pic_name'] ?? null,

                        'position' => $validated['customer_pic_position'] ?? null,

                        'email' => $validated['customer_pic_email'] ?? null,
                        'phone' => $validated['customer_pic_phone'] ?? null,
                    ]
                );
            }



            /*UPDATE DRAFT CONTRACT*/

            if ($request->hasFile('draft_contract')) {

                $file = $request->file('draft_contract');

                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $fileName = $name
                    . '-'
                    . time()
                    . '.'
                    . $file->getClientOriginalExtension();

                $file->move(
                    public_path('upload/request_document'),
                    $fileName
                );

                $filePath =
                    'upload/request_document/' . $fileName;


                $existingFile = TspRequestDocumentFile::where(
                    'request_document_id',
                    $requestDocument->id
                )
                    ->where(
                        'document_type',
                        'Draft Contract'
                    )
                    ->first();


                if ($existingFile) {

                    /*
                    | Hapus file lama jika ada
                    */

                    if (
                        $existingFile->file_path &&
                        file_exists(
                            public_path($existingFile->file_path)
                        )
                    ) {
                        unlink(
                            public_path(
                                $existingFile->file_path
                            )
                        );
                    }


                    $existingFile->update([
                        'name' => $fileName,

                        'file_path' =>
                        $filePath,
                    ]);
                } else {

                    TspRequestDocumentFile::create([
                        'request_document_id' =>
                        $requestDocument->id,

                        'name' => $fileName,

                        'document_type' =>
                        'Draft Contract',

                        'file_path' =>
                        $filePath,
                        'created_by' => Auth::id(),
                    ]);
                }
            }


            /*UPDATE QUOTATION*/

            if ($request->hasFile('quotation')) {

                $file = $request->file('quotation');

                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $fileName = $name
                    . '-'
                    . time()
                    . '.'
                    . $file->getClientOriginalExtension();

                $file->move(
                    public_path('upload/request_document'),
                    $fileName
                );

                $filePath =
                    'upload/request_document/' . $fileName;


                $existingFile = TspRequestDocumentFile::where(
                    'request_document_id',
                    $requestDocument->id
                )
                    ->where(
                        'document_type',
                        'Quotation'
                    )
                    ->first();


                if ($existingFile) {

                    if (
                        $existingFile->file_path &&
                        file_exists(
                            public_path($existingFile->file_path)
                        )
                    ) {
                        unlink(
                            public_path(
                                $existingFile->file_path
                            )
                        );
                    }


                    $existingFile->update([
                        'name' => $fileName,

                        'file_path' =>
                        $filePath,
                    ]);
                } else {

                    TspRequestDocumentFile::create([
                        'request_document_id' =>
                        $requestDocument->id,

                        'name' => $fileName,

                        'document_type' =>
                        'Quotation',

                        'file_path' =>
                        $filePath,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            /* ADD OTHER FILES */
            if ($request->hasFile('other')) {
                foreach ($request->file('other') as $file) {
                    $name = pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    );

                    $fileName = $name
                        . '-'
                        . time()
                        . '.'
                        . $file->getClientOriginalExtension();

                    $file->move(
                        public_path('upload/request_document'),
                        $fileName
                    );

                    $filePath =
                        'upload/request_document/' . $fileName;

                    TspRequestDocumentFile::create([
                        'request_document_id' =>
                        $requestDocument->id,

                        'name' => $fileName,

                        'document_type' =>
                        'Other',

                        'file_path' =>
                        $filePath,
                        'created_by' => Auth::id(),
                    ]);
                }
            }


            /*UPDATE HISTORY*/

            if ($action === 'draft') {

                TspRequestDocumentHistory::create([
                    'request_document_id' =>
                    $requestDocument->id,

                    'stage_id' => 1,
                    'substage_id' => null,
                    'status_id' => 1,
                    'action' => 'Update Draft',
                    'action_by' => Auth::id(),
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);
            } elseif ($action === 'submit') {

                TspRequestDocumentHistory::create([
                    'request_document_id' =>
                    $requestDocument->id,

                    'stage_id' => 1,
                    'substage_id' => null,
                    'status_id' => 2,
                    'action' => 'Submit',
                    'action_by' => Auth::id(),
                    'assigned_to' => getAdminLegalTSP()->first()->id ?? null,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);

                /* SEND EMAIL NOTIFICATION TO ADMIN */
                $detail_email = array(
                    'title' => $requestDocument->title,
                );

                //harusnya diberi kondisi jika status request document sebelum di update adalah draft maka baru kirim email,tapi jika sudah submit maka tidak  kirim email lagi ketika submit di edit
                if ($previousStatusId == 1) {
                    Mail::to(getAdminLegalTSP()->first()->email_sf ?? null)->send(new \App\Mail\TSP\SubmitRequestDocument($detail_email));
                }
            }


            Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return redirect()
                ->route('tsp.request-document')
                ->with(
                    'success',
                    $action === 'draft'
                        ? 'Draft berhasil diperbarui.'
                        : 'Request Document berhasil diperbarui dan disubmit.'
                );
        } catch (\Throwable $e) {
            dd($e);
            $db->rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui data.'
                );
        }
    }

    /**
     * Delete a file associated with a request document.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile($id)
    {
        try {
            $file = TspRequestDocumentFile::findOrFail($id);

            // Hapus file dari storage
            if ($file->file_path && file_exists(public_path($file->file_path))) {
                unlink(public_path($file->file_path));
            }

            // Hapus record dari database
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Display the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDetail($id)
    {
        $requestDocument = TspRequestDocument::leftJoin('satria_legatra.tsp_request_stages', 'satria_legatra.tsp_request_documents.stage_id', '=', 'satria_legatra.tsp_request_stages.id')
            ->leftJoin('satria_legatra.tsp_request_status', 'satria_legatra.tsp_request_documents.status_id', '=', 'satria_legatra.tsp_request_status.id')
            ->leftJoin('satria_legatra.tsp_request_substages', 'satria_legatra.tsp_request_documents.substage_id', '=', 'satria_legatra.tsp_request_substages.id')
            ->select(
                'satria_legatra.tsp_request_documents.*',
                'satria_legatra.tsp_request_stages.stage as stage_name',
                'satria_legatra.tsp_request_status.status as status_name',
                'satria_legatra.tsp_request_substages.substage as substage_name'
            )
            ->findOrFail($id);
        return view('tsp.request-document.detail', compact('requestDocument'));
    }

    /** Get the specified request document detail as JSON.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail($id)
    {
        try {

            $requestDocument = TspRequestDocument::leftJoin('satria.users', 'satria_legatra.tsp_request_documents.requester_id', '=', 'satria.users.id')
                ->select('satria_legatra.tsp_request_documents.*', 'satria.users.name as requester')
                ->with([
                    'customer',
                    'customer.customerPics',
                    'files',
                    'pics',
                ])->findOrFail($id);

            $feedbacks = TspRequestDocumentFeedback::where('satria_legatra.tsp_request_document_feedbacks.request_document_id', $id)
                ->leftJoin('satria_legatra.tsp_request_document_feedback_files', 'satria_legatra.tsp_request_document_feedbacks.id', '=', 'satria_legatra.tsp_request_document_feedback_files.request_document_feedback_id')
                ->leftJoin('satria_legatra.tsp_request_document_histories', 'satria_legatra.tsp_request_document_feedbacks.history_id', '=', 'satria_legatra.tsp_request_document_histories.id')
                ->leftJoin('satria.users', 'satria_legatra.tsp_request_document_histories.action_by', '=', 'satria.users.id')
                ->select(
                    'satria_legatra.tsp_request_document_feedbacks.*',
                    'satria.users.name as action_by_name',
                    'satria_legatra.tsp_request_document_histories.action as history_action',
                    'satria_legatra.tsp_request_document_histories.created_at as created_at',
                    'satria_legatra.tsp_request_document_feedback_files.name as file_name',
                    'satria_legatra.tsp_request_document_feedback_files.file_path as file_path'
                )
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $requestDocument,
                'feedbacks' => $feedbacks,
            ]);
        } catch (\Throwable $e) {

            return redirect()->route('tsp.request-document')
                ->with('error', 'Data Request Document tidak ditemukan.');
        }
    }

    /** Show the cancel confirmation modal for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function cancelConfirmation($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.cancel-confirmation',
            compact('requestDocument')
        );
    }

    /** Cancel Request Document
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $db = DB::connection('legatra');
        try {

            $db->beginTransaction();

            $requestDocument =
                TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([

                'status_id' => 3,

                'stage_id' => 1,

                'substage_id' => null,

                'updated_by' => Auth::id(),

            ]);


            /*INSERT HISTORY*/

            TspRequestDocumentHistory::create([

                'request_document_id' => $requestDocument->id,
                'stage_id' => 1,
                'substage_id' => null,
                'status_id' => 3,
                'action' => 'Cancel',
                'action_by' => Auth::id(),
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            $db->commit();

            return response()->json([

                'success' => true,
                'message' =>
                'Request Document berhasil dibatalkan.',

            ]);
        } catch (\Throwable $e) {
            $db->rollBack();

            return response()->json([

                'success' => false,
                'message' =>
                'Gagal membatalkan Request Document.',

                'error' =>
                $e->getMessage(),

            ], 500);
        }
    }

    /** Show the history modal for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showHistory($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);
        return view('tsp.request-document.modal.history', compact('requestDocument'));
    }

    /** Get the history of the specified request document as JSON.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function history(Request $request, $id)
    {
        try {

            $start = $request->input('start', 0);
            $draw = $request->input('draw', 1);
            $length = $request->input('length', 10);
            $searchValue = $request->input('search.value');

            /*ORDERING*/

            $order = $request->input('order.0');

            $columnIndex = $order['column'] ?? 1;

            $columnName = $request->input(
                "columns.{$columnIndex}.name"
            ) ?? 'satria_legatra.tsp_request_document_histories.created_at';

            $dir = ($order['dir'] ?? 'desc') === 'asc'
                ? 'asc'
                : 'desc';


            /*BASE QUERY*/

            $query = TspRequestDocumentHistory::leftJoin(
                'satria.users',
                'satria_legatra.tsp_request_document_histories.action_by',
                '=',
                'satria.users.id'
            )
                ->select(
                    'satria_legatra.tsp_request_document_histories.id',

                    'satria_legatra.tsp_request_document_histories.created_at as date',

                    'satria_legatra.tsp_request_document_histories.action',

                    'satria.users.name as action_by'
                )
                ->where(
                    'satria_legatra.tsp_request_document_histories.request_document_id',
                    $id
                );


            /*TOTAL DATA*/

            $recordsTotal = TspRequestDocumentHistory::where(
                'request_document_id',
                $id
            )->count();


            /*SEARCH*/

            if (!empty($searchValue)) {

                $query->where(function ($q) use ($searchValue) {

                    $q->where(
                        'satria_legatra.tsp_request_document_histories.action',
                        'like',
                        '%' . $searchValue . '%'
                    )

                        ->orWhere(
                            'satria.users.name',
                            'like',
                            '%' . $searchValue . '%'
                        );
                });
            }


            /*FILTERED TOTAL*/

            $recordsFiltered = $query->count();


            /*PAGINATION*/

            $data = $query
                ->orderBy($columnName, $dir)
                ->skip($start)
                ->take($length)
                ->get();


            /*RESPONSE*/

            return response()->json([

                'draw' => (int) $draw,

                'recordsTotal' => $recordsTotal,

                'recordsFiltered' => $recordsFiltered,

                'data' => $data

            ]);
        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' => 'Gagal mengambil data history.',

                'error' => $e->getMessage()

            ], 500);
        }
    }

    /** Show the decline confirmation modal for the specified request document.
     * @param int $id
     *  @return \Illuminate\View\View
     */
    public function declineConfirmation($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.form-decline',
            compact('requestDocument')
        );
    }

    /** Decline Request Document
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline(Request $request, $id)
    {
        $db = DB::connection('legatra');
        try {
            $validated = $request->validate([
                'remark' => ['required', 'string'],
            ]);


            $db->beginTransaction();

            $requestDocument =
                TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([

                'status_id' => 4,

            ]);

            /*INSERT HISTORY*/

            $history = TspRequestDocumentHistory::create([

                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => 4,
                'action' => 'Decline',
                'action_by' => Auth::id(),
                'assigned_to' => $requestDocument->requester_id,
                'created_by' => Auth::id(),
                'created_at' => now(),

            ]);

            /*INSERT REMARKS DECLINE*/
            TspRequestDocumentFeedback::create([

                'request_document_id' => $requestDocument->id,

                'history_id' => $history->id,

                'stage_id' => $requestDocument->stage_id,

                'substage_id' => $requestDocument->substage_id ?? null,

                'remark' => $validated['remark'],

            ]);

            /* SEND EMAIL NOTIFICATION TO PIC */
            $detail_email = array(
                'title' => $requestDocument->title,
                'remark' => $validated['remark'],
                'subject' => 'Request Document Declined',
                'message' => 'Email Pemberitahuan, request document anda telah ditolak',
            );

            $user = User::find($requestDocument->requester_id);

            Mail::to($user->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));

            $db->commit();

            return response()->json([

                'success' => true,

                'message' =>
                'Request Document berhasil dibatalkan.',

            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak Request Document.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Show the detail view for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showTracking($id)
    {
        $requestDocument = TspRequestDocument::leftJoin('satria_legatra.tsp_request_stages', 'satria_legatra.tsp_request_documents.stage_id', '=', 'satria_legatra.tsp_request_stages.id')
            ->leftJoin('satria_legatra.tsp_request_status', 'satria_legatra.tsp_request_documents.status_id', '=', 'satria_legatra.tsp_request_status.id')
            ->leftJoin('satria_legatra.tsp_request_substages', 'satria_legatra.tsp_request_documents.substage_id', '=', 'satria_legatra.tsp_request_substages.id')
            ->select(
                'satria_legatra.tsp_request_documents.*',
                'satria_legatra.tsp_request_stages.stage as stage_name',
                'satria_legatra.tsp_request_status.status as status_name',
                'satria_legatra.tsp_request_substages.substage as substage_name'
            )
            ->findOrFail($id);

        return view('tsp.request-document.tracking', compact('requestDocument'));
    }

    /** Show the legal drafting view for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showLegalDrafting($id)
    {
        $db = DB::connection('legatra');
        try {

            $db->beginTransaction();

            $requestDocument = TspRequestDocument::findOrFail($id);
            $committee = User::where(function ($query) {
                $query->where(function ($q) {
                    $q->where('companyid', 16731)
                        ->where(function ($qq) {
                            $qq->where('title', 'like', '%Dept Head%')
                                ->orWhere('title', 'like', '%Div Head%')
                                ->orWhere('title', 'like', '%Func Head%')
                                ->orWhere('division', 'Board of Directors');
                        });
                });
            })
                ->orderBy('name')
                ->get();

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([

                'status_id' => 5,

                'stage_id' => 2,

                'substage_id' => null,

                'updated_by' => Auth::id(),

            ]);


            /*INSERT HISTORY*/

            TspRequestDocumentHistory::create([

                'request_document_id' => $requestDocument->id,

                'stage_id' => 2,

                'substage_id' => null,

                'status_id' => 5,

                'action' => 'Legal Drafting',
                'action_by' => Auth::id(),

                'created_by' => Auth::id(),

                'created_at' => now(),

            ]);

            $db->commit();

            return view('tsp.request-document.legal-drafting', compact('requestDocument', 'committee'));
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menampilkan halaman legal drafting.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Store the legal drafting action for the specified request document.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeLegalDrafting(Request $request, $id)
    {
        $db = DB::connection('legatra');
        try {
            $validated = $request->validate([
                'draft_contract' => ['required', 'file', 'mimes:pdf', 'max:10240'],

                'committee_id' => ['required', 'array', 'min:1'],
                'committee_id.*' => ['required', 'integer'],
            ]);

            /*VALIDASI PREFIX FILE*/

            if ($request->hasFile('draft_contract')) {

                $fileName = strtolower(
                    $request
                        ->file('draft_contract')
                        ->getClientOriginalName()
                );

                if (!str_starts_with(
                    $fileName,
                    'draft_contract_'
                )) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'draft_contract' =>
                            'Nama file Draft Contract harus diawali dengan prefix Draft_Contract_.'
                        ]);
                }
            }


            $db->beginTransaction();

            $requestDocument = TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([
                'status_id' => 6,
                'stage_id' => 3,
                'substage_id' => 1,
            ]);

            /*INSERT COMMITTEE*/
            foreach ($validated['committee_id'] as $index => $committeeId) {

                TspRequestDocumentCommittees::create([

                    'request_document_id' => $requestDocument->id,

                    'committee_id' => $committeeId,

                    'sequence' => $index + 1,
                    'verification_ld_status' => false,
                    'verification_flr_status' => false,
                    'created_by' => Auth::id(),
                ]);
            }

            $name = pathinfo(
                $validated['draft_contract']->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $file = $validated['draft_contract'];

            // Buat nama file baru
            $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan langsung ke public/upload/request_document
            $file->move(
                public_path('upload/request_document'),
                $fileName
            );

            // Path yang disimpan ke database
            $path =  'upload/request_document/' . $fileName;

            /*UPDATE DRAFT CONTRACT*/
            TspRequestDocumentFile::updateOrCreate(
                [
                    'request_document_id' => $requestDocument->id,
                    'document_type' => 'Draft Contract',
                ],
                [
                    'name' => $fileName,

                    'file_path' => $path,
                    'updated_by' => Auth::id(),
                ]
            );

            /*INSERT HISTORY*/

            TspRequestDocumentHistory::create([

                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => $requestDocument->status_id,
                'action' => 'Submit Legal Drafting',
                'action_by' => Auth::id(),
                'assigned_to' => $requestDocument->requester_id,
                'created_by' => Auth::id(),
                'created_at' => now(),

            ]);


            /* SEND EMAIL NOTIFICATION TO USER */
            $detail_email = array(
                'title' => $requestDocument->title,
                'subject' => 'Request Document Legal Drafting Completed',
                'message' => 'Request dokumen Anda telah selesai dibuat dan saat ini sudah siap untuk direview.',

            );

            $user = User::find($requestDocument->requester_id);

            Mail::to($user->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));

            Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return redirect()
                ->route('tsp.request-document')
                ->with(
                    'success',
                    'Legal Drafting berhasil disubmit.'
                );
        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Legal Drafting gagal disubmit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Show the request to revision modal for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showRequestToRevisionByUser($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.ld.form-request-to-revision-by-user',
            compact('requestDocument')
        );
    }

    /** Request to Revision LD by User
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function requestToRevisionLDByUser(Request $request, $id)
    {
        $db = DB::connection('legatra');
        try {
            $validated = $request->validate([
                'remark' => ['required', 'string'],
                'attachment' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            ]);

            $db->beginTransaction();

            $requestDocument = TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([
                'status_id' => 10,
                'substage_id' => 3,
            ]);

            /*INSERT HISTORY*/

            $history = TspRequestDocumentHistory::create([
                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => $requestDocument->status_id,
                'action' => 'Request to Revision LD by User',
                'action_by' => Auth::id(),
                'assigned_to' => getAdminLegalTSP()->first()->id ?? null,
            ]);

            /*INSERT REMARKS REQUEST TO REVISION*/
            $feedback = TspRequestDocumentFeedback::create([
                'request_document_id' => $requestDocument->id,
                'history_id' => $history->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'remark' => $validated['remark'],
            ]);

            /*INSERT ATTACHMENT REQUEST TO REVISION */
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/request_document'), $fileName);
                $filePath = 'upload/request_document/' . $fileName;

                TspRequestDocumentFeedbackFile::create([
                    'request_document_feedback_id' => $feedback->id,
                    'name' => $fileName,
                    'file_path' => $filePath,
                ]);
            }

            $data_email = array(
                'title' => $requestDocument->title,
                'remark' => $validated['remark'],
                'subject' => 'Request Document Need Revision',
                'message' => 'Email Pemberitahuan, ada request document yang perlu direvisi',
            );

            Mail::to(getAdminLegalTSP()->first()->email_sf ?? null)->send(new \App\Mail\TSP\RequestDocumentNotification($data_email));

            // Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return response()->json([

                'success' => true,

                'message' => 'Request to Revision berhasil disubmit.',

            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Request to Revision gagal disubmit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Show the legal drafting revision view for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showLegalDraftingRevision($id)
    {
        try {

            $requestDocument = TspRequestDocument::with([
                'committees' => function ($query) {
                    $query->orderBy('sequence', 'asc');
                },
                'files',
            ])->findOrFail($id);

            $draftContract = $requestDocument->files
                ->where('document_type', 'Draft Contract')
                ->first();

            $committee = User::where(function ($query) {
                $query->where(function ($q) {
                    $q->where('companyid', 16731)
                        ->where(function ($qq) {
                            $qq->where('title', 'like', '%Dept Head%')
                                ->orWhere('title', 'like', '%Div Head%')
                                ->orWhere('title', 'like', '%Func Head%')
                                ->orWhere('division', 'Board of Directors');
                        });
                });
            })
                ->orderBy('name')
                ->get();

            // dd($committee);

            return view('tsp.request-document.legal-drafting-revision', compact('requestDocument', 'committee', 'draftContract'));
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menampilkan halaman revisi legal drafting.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Store the legal drafting revision action for the specified request document.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLegalDraftingRevision(Request $request, $id)
    {
        $db = DB::connection('legatra');
        try {
            $validated = $request->validate([
                'draft_contract' => ['required', 'file', 'mimes:pdf', 'max:10240'],

                'committee' => ['required', 'array', 'min:1'],
            ]);

            /*VALIDASI PREFIX FILE*/

            if ($request->hasFile('draft_contract')) {

                $fileName = strtolower(
                    $request
                        ->file('draft_contract')
                        ->getClientOriginalName()
                );

                if (!str_starts_with(
                    $fileName,
                    'draft_contract_'
                )) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'draft_contract' =>
                            'Nama file Draft Contract harus diawali dengan prefix Draft_Contract_.'
                        ]);
                }
            }


            $db->beginTransaction();

            $requestDocument = TspRequestDocument::findOrFail($id);
            $afterCommitteReview = TspRequestDocumentHistory::where('request_document_id', $requestDocument->id)
                ->where('action', 'Request to Revision LD by User After Committee Review')
                ->latest()
                ->first();

            /*UPDATE REQUEST DOCUMENT*/

            if ($afterCommitteReview) {
                $requestDocument->update([
                    'status_id' => 8,
                    'stage_id' => 3,
                    'substage_id' => 2,
                ]);
            } else {
                $requestDocument->update([
                    'status_id' => 6,
                    'stage_id' => 3,
                    'substage_id' => 1,
                ]);
            }


            /*DELETE COMMITTEE KEMUDIAN CREATE ULANG*/
            TspRequestDocumentCommittees::where(
                'request_document_id',
                $requestDocument->id
            )->delete();


            foreach ($validated['committee'] as $index => $committeeId) {

                TspRequestDocumentCommittees::create([

                    'request_document_id' => $requestDocument->id,

                    'committee_id' => $committeeId,

                    'sequence' => $index + 1,

                    'verification_ld_status' => false,

                    'verification_flr_status' => false,

                    'created_by' => Auth::id(),

                ]);
            }

            $name = pathinfo(
                $validated['draft_contract']->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $file = $validated['draft_contract'];

            // Buat nama file baru
            $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan langsung ke public/upload/request_document
            $file->move(
                public_path('upload/request_document'),
                $fileName
            );

            // Path yang disimpan ke database
            $path =  'upload/request_document/' . $fileName;

            /*UPDATE DRAFT CONTRACT*/
            TspRequestDocumentFile::updateOrCreate(
                [
                    'request_document_id' => $requestDocument->id,
                    'document_type' => 'Draft Contract',
                ],
                [
                    'name' => $fileName,

                    'file_path' => $path,
                    'updated_by' => Auth::id(),

                ]
            );

            /*CARI COMMITTEE YANG BELUM VERIFIKASI*/
            $committee = TspRequestDocumentCommittees::where('request_document_id', $requestDocument->id)
                ->where('deleted_at', null)
                ->where('verification_ld_status', false)
                ->first();

            /*INSERT HISTORY*/

            TspRequestDocumentHistory::create([

                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => $requestDocument->status_id,
                'action' => $afterCommitteReview ? 'Revisi Legal Drafting After Review Committe' : 'Revisi Legal Drafting',
                'action_by' => Auth::id(),
                'assigned_to' => $afterCommitteReview ? $committee->committee_id : $requestDocument->requester_id,
                'created_by' => Auth::id(),
                'created_at' => now(),

            ]);


            /* SEND EMAIL NOTIFICATION TO USER */
            $detail_email = array(
                'title' => $requestDocument->title,
                'subject' => 'Request Document Legal Drafting Revision Completed',
                'message' => 'Request dokumen telah selesai direvisi dan saat ini sudah siap untuk direview.',

            );

            $user = User::find($requestDocument->requester_id);
            $committee = User::find($committee->committee_id);

            Mail::to($afterCommitteReview ? $committee->email_sf : $user->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));

            Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return redirect()
                ->route('tsp.request-document.tracking', $requestDocument->id)
                ->with(
                    'success',
                    'Revisi Legal Drafting berhasil disubmit.'
                );
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Revisi Legal Drafting gagal disubmit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Show the verify confirmation modal for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function verifyConfirmation($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.ld.verify-by-user',
            compact('requestDocument')
        );
    }

    /** Verify LD by User
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyLDByUser($id)
    {
        $db = DB::connection('legatra');
        try {

            $db->beginTransaction();

            $requestDocument =
                TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([

                'status_id' => 7,
                'stage_id' => 3,
                'substage_id' => 2,
            ]);

            /*CARI COMMITTEE YANG BELUM VERIFIKASI*/
            $committee = TspRequestDocumentCommittees::where('request_document_id', $requestDocument->id)
                ->where('deleted_at', null)
                ->where('verification_ld_status', false)
                ->first();

            /*INSERT HISTORY*/
            TspRequestDocumentHistory::create([

                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => $requestDocument->status_id,
                'action' => 'Verified by user',
                'action_by' => Auth::id(),
                'assigned_to' => $committee->committee_id,
                'created_by' => Auth::id(),
                'created_at' => now(),

            ]);

            /* SEND EMAIL NOTIFICATION TO PIC */
            $detail_email = array(
                'title' => $requestDocument->title,
                'subject' => 'Request Document Verified by User',
                'message' => 'Email Pemberitahuan, request document telah diverifikasi oleh user dan saat ini sudah siap untuk diverifikasi oleh committee terkait.',
            );

            $committee = User::find($committee->committee_id);

            Mail::to(getAdminLegalTSP()->first()->email_sf ?? null)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));
            Mail::to($committee->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));

            $db->commit();

            return response()->json([

                'success' => true,

                'message' =>
                'Request Document berhasil diverifikasi oleh user.',

            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi Request Document.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Show the request to revision modal for the specified request document by committee.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showRequestToRevisionByCommittee($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.ld.form-request-to-revision-by-committee',
            compact('requestDocument')
        );
    }

    /** Request to Revision LD by Committee
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestToRevisionLDByCommittee(Request $request, $id)
    {
        $db = DB::connection('legatra');
        try {
            $validated = $request->validate([
                'remark' => ['required', 'string'],
                'attachment' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            ]);

            $db->beginTransaction();

            $requestDocument = TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $requestDocument->update([
                'status_id' => 11,
                'substage_id' => 3,
            ]);

            /*INSERT HISTORY*/

            $history = TspRequestDocumentHistory::create([
                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => $requestDocument->status_id,
                'action' => 'Request to Revision LD by Committee',
                'action_by' => Auth::id(),
                'assigned_to' => $requestDocument->requester_id,
            ]);

            /*INSERT REMARKS REQUEST TO REVISION*/
            $feedback = TspRequestDocumentFeedback::create([
                'request_document_id' => $requestDocument->id,
                'history_id' => $history->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'remark' => $validated['remark'],
            ]);

            /*INSERT ATTACHMENT REQUEST TO REVISION */
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $name . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/request_document'), $fileName);
                $filePath = 'upload/request_document/' . $fileName;

                TspRequestDocumentFeedbackFile::create([
                    'request_document_feedback_id' => $feedback->id,
                    'name' => $fileName,
                    'file_path' => $filePath,
                ]);
            }

            /*UPDATE COMMITTEE VERIFICATION STATUS*/
            TspRequestDocumentCommittees::where(
                'request_document_id',
                $requestDocument->id
            )->update([
                'verification_ld_status' => false,
            ]);

            /* SEND EMAIL NOTIFICATION TO USER */
            $data_email = array(
                'title' => $requestDocument->title,
                'remark' => $validated['remark'],
                'subject' => 'Request Document Need Revision',
                'message' => 'Email Pemberitahuan, ada request document yang perlu direvisi',
            );

            $user = User::find($requestDocument->requester_id);

            Mail::to($user->email_sf ?? null)->send(new \App\Mail\TSP\RequestDocumentNotification($data_email));

            // Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return response()->json([

                'success' => true,

                'message' => 'Request to Revision berhasil disubmit.',

            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Request to Revision gagal disubmit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /** Show the revision view for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showRevision($id)
    {
        try {

            $requestDocument = TspRequestDocument::with([
                'customer',
                'files',
            ])->findOrFail($id);

            $draftContract = $requestDocument->files
                ->where('document_type', 'Draft Contract')
                ->first();

            $quotation = $requestDocument->files
                ->where('document_type', 'Quotation')
                ->first();

            return view(
                'tsp.request-document.revision',
                compact(
                    'requestDocument',
                    'draftContract',
                    'quotation'
                )
            );
        } catch (\Throwable $e) {

            return redirect()->route('tsp.request-document')
                ->with('error', 'Data Request Document tidak ditemukan.');
        }
    }


    /** Store the revision for the specified request document.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRevision(Request $request, $id)
    {
        $action = $request->input('action_type');
        $requestDocument = TspRequestDocument::findOrFail($id);
        $draftContract = TspRequestDocumentFile::where('request_document_id', $requestDocument->id)
            ->where(
                'document_type',
                'Draft Contract'
            )
            ->first();
        $quotation = TspRequestDocumentFile::where('request_document_id', $requestDocument->id)
            ->where(
                'document_type',
                'Quotation'
            )
            ->first();

        /*VALIDATION */

        if ($action === 'draft') {

            $rules = [

                'title' => ['required', 'string', 'max:255'],
                // Optional fields
                'contract_type' => [
                    'nullable',
                    Rule::in([
                        'Part',
                        'Service',
                        'Reman',
                        'Unit'
                    ])
                ],

                'potential_amount' => ['nullable', 'numeric'],

                'sign_status' => [
                    'nullable',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'is_project' => ['nullable', 'boolean'],

                'sow' => ['nullable', 'string'],
                'transaction_procedure' => ['nullable', 'string'],
                'kpi' => ['nullable', 'string'],

                'pic_name' => ['nullable', 'string', 'max:255'],

                'pic_position' => ['nullable', 'string', 'max:255'],


                'pic_email' => ['nullable', 'email', 'max:255'],

                'pic_phone' => ['nullable', 'string', 'max:50'],
                'draft_contract' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

                'quotation' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

                'customer_id' => ['nullable'],

                'customer_name' => ['nullable', 'string', 'max:255'],
                'customer_nib' => ['nullable', 'string', 'max:255'],
                'customer_npwp' => ['nullable', 'string', 'max:255'],
                'customer_address' => ['nullable', 'string', 'max:255'],
                'customer_postal_code' => ['nullable', 'string', 'max:10'],
                'customer_email' => ['nullable', 'email', 'max:255'],
                'customer_pic_name' => ['nullable', 'string', 'max:255'],
                'customer_pic_position' => ['nullable', 'string', 'max:255'],
                'customer_pic_email' => ['nullable', 'email', 'max:255'],
                'customer_pic_phone' => ['nullable', 'string', 'max:50'],
            ];
        } else {

            $rules = [

                'title' => ['required', 'string', 'max:255'],
                'contract_type' => ['required', Rule::in(['Part', 'Service', 'Reman', 'Unit'])],
                'potential_amount' => ['required', 'numeric', 'min:0'],
                'sign_status' => [
                    'required',
                    Rule::in([
                        'Not Signed',
                        'Partial Signed',
                        'Fully Signed'
                    ])
                ],

                'is_project' => ['required', 'boolean'],

                'sow' => ['required', 'string'],

                'transaction_procedure' => ['required', 'string'],

                'kpi' => ['required', 'string'],

                'pic_name' => ['required', 'string', 'max:255'],


                'pic_position' => ['required', 'string', 'max:255'],

                'pic_email' => ['required', 'email', 'max:255'],


                'pic_phone' => ['required', 'string', 'max:50'],

                'draft_contract' => [($requestDocument->status_id == 1 && !$draftContract) ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],

                'quotation' => [($requestDocument->status_id == 1 && !$quotation) ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],

                'customer_id' => ['required'],
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_nib' => ['required', 'string', 'max:255'],
                'customer_npwp' => ['required', 'string', 'max:255'],
                'customer_address' => ['required', 'string', 'max:255'],
                'customer_postal_code' => ['required', 'string', 'max:10'],

                'customer_email' => ['required', 'email', 'max:255'],
                'customer_pic_name' => ['required', 'string', 'max:255'],
                'customer_pic_position' => ['required', 'string', 'max:255'],

                'customer_pic_email' => ['required', 'email', 'max:255'],

                'customer_pic_phone' => ['required', 'string', 'max:50'],


            ];
        }

        $validated = $request->validate($rules);


        /*VALIDASI PREFIX FILE*/

        if ($request->hasFile('draft_contract')) {

            $fileName = strtolower(
                $request
                    ->file('draft_contract')
                    ->getClientOriginalName()
            );

            if (!str_starts_with(
                $fileName,
                'draft_contract_'
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'draft_contract' =>
                        'Nama file Draft Contract harus diawali dengan prefix Draft_Contract_.'
                    ]);
            }
        }

        if ($request->hasFile('quotation')) {

            $fileName = strtolower(
                $request
                    ->file('quotation')
                    ->getClientOriginalName()
            );

            if (!str_starts_with(
                $fileName,
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


        /*UPDATE*/

        $db = DB::connection('legatra');

        $db->beginTransaction();
        try {

            $requestDocument = TspRequestDocument::findOrFail($id);

            /* UPDATE REQUEST DOCUMENT */

            $requestDocument->update([
                'stage_id' => 3,
                'status_id' => 10,
                'substage_id' => 3,

                'title' => $validated['title'],

                'contract_type' => $validated['contract_type']
                    ?? $requestDocument->contract_type,

                'potential_amount' => $validated['potential_amount']
                    ?? $requestDocument->potential_amount,

                'sign_status' => $validated['sign_status']
                    ?? $requestDocument->sign_status,

                'is_project' => (bool) $validated['is_project']
                    ?? $requestDocument->is_project,

                'sow' => $validated['sow']
                    ?? $requestDocument->sow,

                'transaction_procedure' =>
                $validated['transaction_procedure']
                    ?? $requestDocument->transaction_procedure,

                'kpi' => $validated['kpi']
                    ?? $requestDocument->kpi,


            ]);


            // UPDATE PIC
            if (
                !empty($validated['pic_name'])
            ) {
                $pic = TspRequestDocumentPic::updateOrCreate(
                    [
                        'request_document_id' => $requestDocument->id,
                    ],
                    [
                        'name' => $validated['pic_name'] ?? null,

                        'position' => $validated['pic_position'] ?? null,

                        'email' => $validated['pic_email'] ?? null,

                        'phone' => $validated['pic_phone'] ?? null,
                    ]
                );
            }



            /*  UPDATE CUSTOMER */
            if (
                !empty($validated['customer_name'])
            ) {
                $customer = TspRequestDocumentCustomer::updateOrCreate(
                    [
                        'request_document_id' => $requestDocument->id,
                    ],
                    [
                        'name' => $validated['customer_name'] ?? null,

                        'nib' => $validated['customer_nib'] ?? null,

                        'npwp' => $validated['customer_npwp'] ?? null,

                        'address' => $validated['customer_address'] ?? null,
                        'postal_code' => $validated['customer_postal_code'] ?? null,

                        'email' => $validated['customer_email'] ?? null,
                    ]
                );
            }


            /*  UPDATE CUSTOMER PIC */
            if (
                !empty($validated['customer_id']) ||
                !empty($validated['customer_pic_name'])
            ) {
                TspRequestDocumentCustomerPic::updateOrCreate(
                    [
                        'request_document_customer_id' => $customer->id,
                    ],
                    [
                        'name' => $validated['customer_pic_name'] ?? null,

                        'position' => $validated['customer_pic_position'] ?? null,

                        'email' => $validated['customer_pic_email'] ?? null,
                        'phone' => $validated['customer_pic_phone'] ?? null,
                    ]
                );
            }



            /*UPDATE DRAFT CONTRACT*/

            if ($request->hasFile('draft_contract')) {

                $file = $request->file('draft_contract');

                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $fileName = $name
                    . '-'
                    . time()
                    . '.'
                    . $file->getClientOriginalExtension();

                $file->move(
                    public_path('upload/request_document'),
                    $fileName
                );

                $filePath =
                    'upload/request_document/' . $fileName;


                $existingFile = TspRequestDocumentFile::where(
                    'request_document_id',
                    $requestDocument->id
                )
                    ->where(
                        'document_type',
                        'Draft Contract'
                    )
                    ->first();


                if ($existingFile) {

                    /*
                    | Hapus file lama jika ada
                    */

                    if (
                        $existingFile->file_path &&
                        file_exists(
                            public_path($existingFile->file_path)
                        )
                    ) {
                        unlink(
                            public_path(
                                $existingFile->file_path
                            )
                        );
                    }


                    $existingFile->update([
                        'name' => $fileName,

                        'file_path' =>
                        $filePath,
                    ]);
                } else {

                    TspRequestDocumentFile::create([
                        'request_document_id' =>
                        $requestDocument->id,

                        'name' => $fileName,

                        'document_type' =>
                        'Draft Contract',

                        'file_path' =>
                        $filePath,
                        'created_by' => Auth::id(),
                    ]);
                }
            }


            /*UPDATE QUOTATION*/

            if ($request->hasFile('quotation')) {

                $file = $request->file('quotation');

                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $fileName = $name
                    . '-'
                    . time()
                    . '.'
                    . $file->getClientOriginalExtension();

                $file->move(
                    public_path('upload/request_document'),
                    $fileName
                );

                $filePath =
                    'upload/request_document/' . $fileName;


                $existingFile = TspRequestDocumentFile::where(
                    'request_document_id',
                    $requestDocument->id
                )
                    ->where(
                        'document_type',
                        'Quotation'
                    )
                    ->first();


                if ($existingFile) {

                    if (
                        $existingFile->file_path &&
                        file_exists(
                            public_path($existingFile->file_path)
                        )
                    ) {
                        unlink(
                            public_path(
                                $existingFile->file_path
                            )
                        );
                    }


                    $existingFile->update([
                        'name' => $fileName,

                        'file_path' =>
                        $filePath,
                    ]);
                } else {

                    TspRequestDocumentFile::create([
                        'request_document_id' =>
                        $requestDocument->id,

                        'name' => $fileName,

                        'document_type' =>
                        'Quotation',

                        'file_path' =>
                        $filePath,
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            /*UPDATE COMMITTEE VERIFICATION STATUS*/
            TspRequestDocumentCommittees::where(
                'request_document_id',
                $requestDocument->id
            )->update([
                'verification_ld_status' => false,
            ]);


            /*UPDATE HISTORY*/

            TspRequestDocumentHistory::create([
                'request_document_id' =>
                $requestDocument->id,

                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id,
                'status_id' => $requestDocument->status_id,
                'action' => 'Request to Revision LD by User After Committee Review',
                'action_by' => Auth::id(),
                'assigned_to' => getAdminLegalTSP()->first()->id ?? null,
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            /* SEND EMAIL NOTIFICATION TO ADMIN */
            $detail_email = array(
                'title' => $requestDocument->title,
                'subject' => 'Request Document Need Revision',
                'message' => 'Email Pemberitahuan, ada request document yang perlu direvisi',
            );

            Mail::to(getAdminLegalTSP()->first()->email ?? null)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));


            Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return redirect()
                ->route('tsp.request-document.tracking', $requestDocument->id)
                ->with(
                    'success',
                    'Request Document berhasil diperbarui dan disubmit.'
                );
        } catch (\Throwable $e) {
            dd($e);
            $db->rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memperbarui data.'
                );
        }
    }

    /** Show the verify confirmation modal for the specified request document by committee.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function verifyConfirmationCommittee($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.ld.verify-by-committee',
            compact('requestDocument')
        );
    }

    /** Verify LD by Committee
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyLDByCommittee($id)
    {
        $db = DB::connection('legatra');

        try {

            $db->beginTransaction();


            /*GET REQUEST DOCUMENT*/
            $requestDocument = TspRequestDocument::findOrFail($id);

            /*CARI COMMITTEE YANG SEDANG LOGIN */
            $currentCommittee = TspRequestDocumentCommittees::where(
                'request_document_id',
                $requestDocument->id
            )
                ->where('committee_id', Auth::id())
                ->where('verification_ld_status', false)
                ->whereNull('deleted_at')
                ->first();


            /*VALIDASI COMMITTEE*/
            if (!$currentCommittee) {

                throw new \Exception(
                    'Anda bukan committee yang sedang mendapatkan giliran untuk melakukan verifikasi.'
                );
            }


            /*UPDATE STATUS VERIFIKASI COMMITTEE SAAT INI*/
            $currentCommittee->update([
                'verification_ld_status' => true,

            ]);


            /*CEK COMMITTEE YANG MASIH BELUM VERIFIKASI*/
            $nextCommittee = TspRequestDocumentCommittees::where(
                'request_document_id',
                $requestDocument->id
            )
                ->where('verification_ld_status', false)
                ->whereNull('deleted_at')
                ->orderBy('sequence', 'asc')
                ->first();


            /*JIKA MASIH ADA COMMITTEE BERIKUTNYA*/
            if ($nextCommittee) {

                /*UPDATE REQUEST DOCUMENT*/
                $requestDocument->update([

                    'status_id' => 8,

                    'stage_id' => 3,

                    'substage_id' => 2,

                ]);


                /*INSERT HISTORY*/
                TspRequestDocumentHistory::create([

                    'request_document_id' => $requestDocument->id,

                    'stage_id' => $requestDocument->stage_id,

                    'substage_id' => $requestDocument->substage_id,

                    'status_id' => $requestDocument->status_id,

                    'action' => 'Verified by Committee',

                    'action_by' => Auth::id(),

                    'assigned_to' => $nextCommittee->committee_id,

                    'created_by' => Auth::id(),

                    'created_at' => now(),

                ]);


                /*GET NEXT COMMITTEE USER*/

                $nextCommitteeUser = User::find(
                    $nextCommittee->committee_id
                );


                /*SEND EMAIL TO NEXT COMMITTEE*/

                if (
                    $nextCommitteeUser &&
                    $nextCommitteeUser->email_sf
                ) {

                    $detail_email = [

                        'title' => $requestDocument->title,

                        'subject' => 'Request Document Menunggu Verifikasi',

                        'message' =>
                        'Request Document telah diverifikasi oleh committee sebelumnya dan saat ini menunggu verifikasi Anda.',

                    ];


                    Mail::to($nextCommitteeUser->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));
                }


                $db->commit();


                return response()->json([

                    'success' => true,

                    'message' =>
                    'Verifikasi berhasil. Request Document telah diteruskan ke committee berikutnya.',

                ]);
            } else {

                /* JIKA SEMUA COMMITTEE SUDAH VERIFIKASI 
                UPDATE REQUEST DOCUMENT*/

                $requestDocument->update([
                    'status_id' => 9,
                    'stage_id' => 4,
                    'substage_id' => null,

                ]);


                /*INSERT HISTORY*/

                TspRequestDocumentHistory::create([

                    'request_document_id' => $requestDocument->id,

                    'stage_id' => $requestDocument->stage_id,

                    'substage_id' => null,

                    'status_id' => $requestDocument->status_id,

                    'action' => 'All Committees Verified',

                    'action_by' => Auth::id(),

                    'assigned_to' => $requestDocument->requester_id,

                    'created_by' => Auth::id(),

                    'created_at' => now(),

                ]);


                /* GET USER / REQUESTER */
                $requester = User::find($requestDocument->requester_id);

                /* GET ADMIN LEGAL */
                $adminLegal = getAdminLegalTSP()->first();


                /* EMAIL DETAIL */
                $detail_email = [

                    'title' => $requestDocument->title,
                    'subject' => 'Request Document Verified by Committee',
                    'message' => 'Seluruh Committee telah melakukan verifikasi. Request Document telah selesai diverifikasi oleh Committee dan akan dilanjutkan ke proses Negotiation.',

                ];


                /* SEND EMAIL TO USER */
                if ($requester && $requester->email_sf) {
                    Mail::to($requester->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));
                }


                /* SEND EMAIL TO ADMIN LEGAL */

                if ($adminLegal && $adminLegal->email_sf) {
                    Mail::to($adminLegal->email_sf)->send(new \App\Mail\TSP\RequestDocumentNotification($detail_email));
                }

                $db->commit();


                return response()->json([

                    'success' => true,

                    'message' =>
                    'Semua Committee telah melakukan verifikasi. Request Document berhasil dilanjutkan ke tahap Negotiation.',

                ]);
            }
        } catch (ValidationException $e) {

            if ($db->transactionLevel() > 0) {
                $db->rollBack();
            }


            return response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $e->errors(),

            ], 422);
        } catch (\Throwable $e) {

            if ($db->transactionLevel() > 0) {
                $db->rollBack();
            }


            return response()->json([

                'success' => false,

                'message' =>
                'Gagal memverifikasi Request Document.',

                'error' => $e->getMessage(),

            ], 500);
        }
    }

    /** Show the upload final document modal for the specified request document.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showUploadFinalDocument($id)
    {
        $requestDocument = TspRequestDocument::findOrFail($id);

        return view(
            'tsp.request-document.modal.form-upload-final-document',
            compact('requestDocument')
        );
    }

    /** Upload the final document for the specified request document.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFinalDocument(Request $request, $id)
    {
        $db = DB::connection('legatra');
        try {
            $validated = $request->validate([
                'final_document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            ]);

            $db->beginTransaction();

            $requestDocument = TspRequestDocument::findOrFail($id);

            /*UPDATE REQUEST DOCUMENT*/

            $documentNumber = (function () {
                $prefix = 'Lgl/Agreement/TRIATRA/';

                $lastNo = TspRequestDocument::whereNotNull('document_number')
                    ->where('document_number', 'like', $prefix . '%')
                    ->lockForUpdate()
                    ->selectRaw("MAX(CAST(SUBSTRING_INDEX(document_number, '/', -1) AS UNSIGNED)) as last_no")
                    ->value('last_no');

                $nextNo = ((int) $lastNo) + 1;

                return $prefix . $nextNo;
            })();

            $requestDocument->update([
                'status_id' => 13,
                'stage_id' => 5,
                'substage_id' => null,
                'document_number' => $documentNumber,
            ]);

            /*INSERT HISTORY*/

            TspRequestDocumentHistory::create([
                'request_document_id' => $requestDocument->id,
                'stage_id' => $requestDocument->stage_id,
                'substage_id' => $requestDocument->substage_id ?? null,
                'status_id' => $requestDocument->status_id,
                'action' => 'Upload Final Document',
                'action_by' => Auth::id(),
                'assigned_to' => getAdminLegalTSP()->first()->id ?? null,
            ]);

            /*INSERT FILE*/
            if ($request->hasFile('final_document')) {

                $file = $validated['final_document'];

                $name = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $fileName = $name
                    . '-'
                    . time()
                    . '.'
                    . $file->getClientOriginalExtension();

                $file->move(
                    public_path('upload/request_document'),
                    $fileName
                );

                $filePath = 'upload/request_document/' . $fileName;

                TspRequestDocumentFile::create([
                    'request_document_id' => $requestDocument->id,

                    'name' => $fileName,

                    'document_type' => 'Final Contract',

                    'file_path' => $filePath,
                    'created_by' => Auth::id(),
                ]);
            }

            $data_email = array(
                'title' => $requestDocument->title,
                'subject' => 'Final Document Uploaded',
                'message' => 'Email Pemberitahuan, Final Document telah diunggah dan siap untuk diproses lebih lanjut oleh Admin Legal.',
            );

            Mail::to(getAdminLegalTSP()->first()->email_sf ?? null)->send(new \App\Mail\TSP\RequestDocumentNotification($data_email));

            // Alert::success('Data Saved Successfully', 'Success Message');
            $db->commit();
            return response()->json([

                'success' => true,
                'message' => 'Final Document berhasil diunggah.',

            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {

            $db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Final Document gagal disubmit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
