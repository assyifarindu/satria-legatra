<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;
use App\Models\Table\TspRequestDocument;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

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
            $query = TspRequestDocument::select(
                'id', 'stage_id', 'substage_id', 'status_id', 
                'document_number', 'title', 'contract_type', 
                'requester_id', 'potential_amount', 'sign_status', 
                'is_project', 'created_at', 'updated_at'
            );

            // 4. Filtering Search
            if (!empty($searchValue)) {
                $query->where(function($q) use ($searchValue) {
                    $q->where('document_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('title', 'like', '%' . $searchValue . '%')
                    ->orWhere('contract_type', 'like', '%' . $searchValue . '%')
                    ->orWhere('requester_id', 'like', '%' . $searchValue . '%');
                });
            }

            // 5. Hitung total record setelah di-filter
            $recordsFiltered = $query->count();

            // 6. Ambil data terpaginasi
            $data = $query->orderBy($columnName, $dir)
                        ->skip($start)
                        ->take($length)
                        ->get();
                        
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
        try {
            dd($request->all());
        } catch(Exception $e) {
            dd($e);
            return response()->json([
                "success"       => false,
                "error_message" => $e->getMessage(),
                "message"       => "An error has occurred!"
            ], 500);
        }
    }
}
