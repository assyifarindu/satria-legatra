<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;
use App\Models\Table\TspRequestDocument;
use App\Models\Table\TspRequestDocumentCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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

    public function index()
    {
        return view('tsp.dashboard.index');
    }

    public function getContractTypeComposition()
    {
        try {

            $baseQuery = TspRequestDocument::where('sign_status', 'Fully Signed');

            $unit = (clone $baseQuery)
                ->where('contract_type', 'Unit')
                ->count();

            $service = (clone $baseQuery)
                ->where('contract_type', 'Service')
                ->count();

            $parts = (clone $baseQuery)
                ->where('contract_type', 'Part')
                ->count();

            $reman = (clone $baseQuery)
                ->where('contract_type', 'Reman')
                ->count();

            $total = $baseQuery->count();

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved contract type composition data.',
                'data' => [
                    'unit' => $unit,
                    'service' => $service,
                    'parts' => $parts,
                    'reman' => $reman,
                    'total' => $total,
                ]
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contract type composition data.',
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPendingLegalReview()
    {
        try {

            $pendingLegal = TspRequestDocument::whereIn('status_id', [2, 3])
                ->count();

            // Sesuaikan formula SLA sesuai definisi yang kamu gunakan
            $totalSlaStandard = TspRequestDocument::whereIn('status_id', [2, 3])
                ->count();

            $totalActualSla = TspRequestDocument::whereIn('status_id', [2, 3])
                ->whereNotNull('updated_at')
                ->count();

            $achSlaLegal = $totalSlaStandard > 0
                ? round(($totalSlaStandard * 100) / $totalActualSla)
                : 0;

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pending legal review data.',
                'data' => [
                    'pending_legal' => 24,
                    'ach_sla_legal' => 32,
                    'aging_due_date_agreement' => [
                        'less_than_30_days' => 6,
                        'between_30_and_60_days' => 9,
                        'between_60_and_90_days' => 9,
                    ]
                ]
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending legal review data.',
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPendingReview()
    {
        try {

            $userReview = TspRequestDocument::whereIn('substage_id', [1, 4])
                ->count();

            $committeeReview = TspRequestDocument::whereIn('substage_id', [2, 5])
                ->count();

            $bodReview = TspRequestDocument::where('stage_id', 7)
                ->count();


            /*
            |--------------------------------------------------------------------------
            | SLA
            |--------------------------------------------------------------------------
            | Formula SLA akan disesuaikan setelah nama field
            | SLA Standard dan Actual SLA sudah diketahui.
            |--------------------------------------------------------------------------
            */

            $achSlaUser = 0;
            $achSlaCommittee = 0;


            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pending review data.',
                'data' => [

                    'user_review' => 24,

                    'committee_review' => 12,

                    'bod_review' => 6,

                    'ach_sla_user' => 78,

                    'ach_sla_committee' => 65,

                ]
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending review data.',
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPendingSignCustomer()
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Total Pending Sign Customer
            |--------------------------------------------------------------------------
            */
            $total = TspRequestDocument::where('sign_status', 'Partial Signed')
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Top 5 Customer Pending Sign
            |--------------------------------------------------------------------------
            */
            $topCustomers = TspRequestDocumentCustomer::join(
                'tsp_request_documents as documents',
                'tsp_request_document_customers.request_document_id',
                '=',
                'documents.id'
            )
                ->where(
                    'documents.sign_status',
                    'Partial Signed'
                )
                ->select(
                    'tsp_request_document_customers.name as customer_name',
                    DB::raw('COUNT(*) as pending_sign_count')
                )
                ->groupBy('tsp_request_document_customers.name')
                ->orderByDesc('pending_sign_count')
                ->limit(5)
                ->get();

            $data = [
                'total' => 5,

                'top_5_customers' => [
                    [
                        'customer_name' => 'Sapta Indra Sejati',
                        'pending_sign_count' => 3,
                    ],
                    [
                        'customer_name' => 'Pamapersada',
                        'pending_sign_count' => 2,
                    ],
                    [
                        'customer_name' => 'Kalimantan Prima Coal',
                        'pending_sign_count' => 0,
                    ],
                    [
                        'customer_name' => 'United Tractors',
                        'pending_sign_count' => 0,
                    ],
                    [
                        'customer_name' => 'Putra Perkasa Abadi',
                        'pending_sign_count' => 0,
                    ],
                ],
            ];


            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pending sign customer data.',
                'data' => $data
                // [
                //     'total' => $total,

                //     'top_5_customers' => $topCustomers
                //         ->map(function ($customer) {
                //             return [
                //                 'customer_name' => $customer->customer_name,
                //                 'pending_sign_count' => (int) $customer->pending_sign_count,
                //             ];
                //         })
                //         ->values(),
                // ]
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending sign customer data.',
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getContractCustomerAllSite()
    {
        try {

            $data = [
                [
                    'site' => 'Jakarta',
                    'total_contract' => 90,
                ],
                [
                    'site' => 'Surabaya',
                    'total_contract' => 80,
                ],
                [
                    'site' => 'Balikpapan',
                    'total_contract' => 70,
                ],
                [
                    'site' => 'Samarinda',
                    'total_contract' => 60,
                ],
                [
                    'site' => 'Makassar',
                    'total_contract' => 50,
                ],
                [
                    'site' => 'Banjarmasin',
                    'total_contract' => 40,
                ],
                [
                    'site' => 'Palembang',
                    'total_contract' => 30,
                ],
                [
                    'site' => 'Medan',
                    'total_contract' => 20,
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved contract customer all site data.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contract customer all site data.',
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
