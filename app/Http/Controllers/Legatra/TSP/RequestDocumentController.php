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

    // Show the index page for request documents
    public function index()
    {
        return view('tsp.request-document.index');
    }

    private function generateDummyData($count)
    {
        $statuses     = ['Draft', 'Pending', 'Approved', 'Rejected'];
        $contracts    = ['NDA', 'Vendor Agreement', 'Employment', 'Service Level Agreement'];
        $requesters   = ['Ahmad Yani', 'Siti Nurhaliza', 'Budi Santoso', 'Rina Nose', 'Dewi Lestari'];
        $signStatuses = ['Pending', 'Signed', 'In Review'];
        $categories   = ['Internal IT', 'Procurement', 'Human Resource', 'Marketing Campaign'];

        $data = [];
        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'id'               => $i,
                'status'           => $statuses[array_rand($statuses)],
                'document_number'  => 'DOC/TSP/2026/' . sprintf('%03d', $i),
                'title'            => 'Dokumen Pengajuan Kerjasama #' . $i,
                'contract_type'    => $contracts[array_rand($contracts)],
                'requester'        => $requesters[array_rand($requesters)],
                'sign_status'      => $signStatuses[array_rand($signStatuses)],
                'is_project' => $categories[array_rand($categories)],
            ];
        }

        return $data;
    }

    // Get data request documents
    public function data(Request $request) 
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
}
