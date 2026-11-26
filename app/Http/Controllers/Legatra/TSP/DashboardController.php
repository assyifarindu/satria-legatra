<?php

namespace App\Http\Controllers\Legatra\TSP;

use App\Http\Controllers\Controller;
use App\Models\Table\TspFormLegalReview;
use App\Models\Table\TspRequestDocument;
use App\Models\Table\TspRequestDocumentCustomer;
use App\Models\Table\TspRequestDocumentHistory;
use App\Models\Table\TspRequestStage;
use App\Models\Table\TspRequestSubstage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

            $baseQuery = TspRequestDocument::where('sign_status', 'Fully Signed')->whereNull('deleted_at');

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

            $pendingLegal = TspRequestDocument::whereIn('stage_id', [2])
                ->whereNull('deleted_at')
                ->count();

            // ========================================
            // 1. TOTAL SLA STANDARD
            // ========================================

            $stage2 = TspRequestStage::find(2);

            $totalDocumentsStage2 = TspRequestDocument::where('stage_id', 2)->whereNull('deleted_at')->count();

            // sla_hours
            $totalSlaStandardStage2 = ($stage2->sla_hours ?? 0) * $totalDocumentsStage2;

            $totalSlaStandard = $totalSlaStandardStage2;


            // ========================================
            // 2. AMBIL DATA HISTORY
            // ========================================
            $requestDocumentIds = TspRequestDocument::whereIn('stage_id', [2])->whereNull('deleted_at')->pluck('id')->toArray();

            $histories = TspRequestDocumentHistory::whereIn('request_document_id', $requestDocumentIds)
                ->whereNotNull('created_at')
                ->orderBy('request_document_id')
                ->orderBy('created_at')
                ->get()
                ->groupBy('request_document_id');



            // ========================================
            // 3. HITUNG ACTUAL SLA PER DOKUMEN
            // ========================================

            $totalActualSlaStage2 = 0;

            $countActualStage2 = 0;

            $today = Carbon::today();

            foreach ($histories as $documentId => $documentHistories) {

                // History sudah diurutkan berdasarkan created_at ascending.
                $documentHistories = $documentHistories->values();

                // Ambil history Stage 2 paling terakhir
                $lastStage2 = $documentHistories
                    ->filter(fn($history) => (int) $history->stage_id === 2)
                    ->last();

                // Ambil stage terakhir dari Stage 2 
                // berdasarkan created_at yang paling baru.
                $lastHistory = collect([$lastStage2])
                    ->filter()
                    ->sortBy('created_at')
                    ->last();

                if (!$lastHistory) {
                    continue;
                }

                $startDate = Carbon::parse($lastHistory->created_at);
                $actualSla = round(
                    ($startDate)->diffInSeconds(Carbon::now()) / 3600,
                    2
                );

                if ((int) $lastHistory->stage_id === 2) {
                    $totalActualSlaStage2 += $actualSla;
                    $countActualStage2++;
                }
            }

            // ========================================
            // 4. TOTAL ACTUAL SLA DAN ACHIEVEMENT
            // ========================================

            $totalActualSla = $totalActualSlaStage2;

            $achSlaLegal = $totalActualSla > 0
                ? round(($totalSlaStandard / $totalActualSla) * 100, 2)
                : 0;


            $agingDueDateAggrement = TspFormLegalReview::join('tsp_request_documents as documents', 'tsp_form_legal_reviews.request_document_id', '=', 'documents.id')
                ->where('documents.stage_id', 11)
                ->whereNotNull('tsp_form_legal_reviews.end_date')
                ->whereNull('documents.deleted_at')
                ->get()
                ->groupBy(function ($item) {
                    $dueDate = Carbon::parse($item->end_date);
                    $today = Carbon::today();
                    $diffInDays = $today->diffInDays($dueDate, false);

                    if ($diffInDays < 30) {
                        return 'less_than_30_days';
                    } elseif ($diffInDays >= 30 && $diffInDays < 60) {
                        return 'between_30_and_60_days';
                    } elseif ($diffInDays >= 60 && $diffInDays < 90) {
                        return 'between_60_and_90_days';
                    } else {
                        return 'more_than_90_days';
                    }
                })
                ->map(function ($group) {
                    return count($group);
                });

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pending legal review data.',
                'data' => [
                    'pending_legal' => $pendingLegal,
                    'ach_sla_legal' => $achSlaLegal,
                    'aging_due_date_agreement' => [
                        'less_than_30_days' => $agingDueDateAggrement->get('less_than_30_days', 0),
                        'between_30_and_60_days' => $agingDueDateAggrement->get('between_30_and_60_days', 0),
                        'between_60_and_90_days' => $agingDueDateAggrement->get('between_60_and_90_days', 0),
                        'more_than_90_days' => $agingDueDateAggrement->get('more_than_90_days', 0),
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

            $userReview = TspRequestDocument::whereIn('substage_id', [4])
                ->whereNull('deleted_at')
                ->count();

            $committeeReview = TspRequestDocument::whereIn('substage_id', [5])
                ->whereNull('deleted_at')
                ->count();

            $bodReview = TspRequestDocument::where('stage_id', 7)
                ->whereNull('deleted_at')
                ->count();


            // HITUNG SLA ACHIEVEMENT USER
            // 1. TOTAL SLA STANDARD

            $subStage4 = TspRequestSubstage::find(4);

            $totalDocumentsSubStage4 = TspRequestDocument::where('substage_id', 4)->whereNull('deleted_at')->count();

            // sla_hours
            $totalSlaStandardSubStage4 = ($subStage4->sla_hours ?? 0) * $totalDocumentsSubStage4;


            // 2. AMBIL DATA HISTORY

            $requestDocumentIds = TspRequestDocument::where(function ($query) {
                $query->whereIn('stage_id', [7, 8, 9, 10, 11])
                    ->orWhereIn('substage_id', [5, 6]);
            })
                ->whereNull('deleted_at')->pluck('id')->toArray();

            // dd($requestDocumentIds);

            $histories = TspRequestDocumentHistory::whereIn('request_document_id', $requestDocumentIds)
                ->whereNotNull('created_at')
                ->orderBy('request_document_id')
                ->orderBy('created_at')
                ->get()
                ->groupBy('request_document_id');


            // 3. KONFIGURASI STAGE DAN SUBSTAGE
            $conditions = [
                'stage_7' => fn($h) => (int) $h->stage_id === 7,
                'stage_8' => fn($h) => (int) $h->stage_id === 8,
                'stage_9' => fn($h) => (int) $h->stage_id === 9,
                'stage_10' => fn($h) => (int) $h->stage_id === 10,
                'stage_11' => fn($h) => (int) $h->stage_id === 11,
                'substage_5' => fn($h) => (int) $h->substage_id === 5,
                'substage_6' => fn($h) => (int) $h->substage_id === 6,
            ];

            $totalActualSlaUser = 0;
            $slaSummary = [];

            $now = Carbon::now();


            // 4. HITUNG ACTUAL SLA
            foreach ($histories as $documentHistories) {

                $lastHistory = collect($conditions)
                    ->map(
                        fn($condition) => $documentHistories
                            ->filter($condition)
                            ->last()
                    )
                    ->filter()
                    ->sortBy('created_at')
                    ->last();

                if (!$lastHistory) {
                    continue;
                }

                $actualSla = round(
                    Carbon::parse($lastHistory->created_at)->diffInSeconds($now) / 3600,
                    2
                );

                $totalActualSlaUser += $actualSla;
            }

            // 5. HITUNG ACHIEVEMENT
            $achSlaUser = $totalActualSlaUser > 0
                ? round(($totalSlaStandardSubStage4 / $totalActualSlaUser) * 100, 2)
                : 0;


            // HITUNG SLA ACHIEVEMENT COMMITTEE
            // 1. TOTAL SLA STANDARD

            $subStage4 = TspRequestSubstage::find(5);

            $totalDocumentsSubStage5 = TspRequestDocument::where('substage_id', 5)->whereNull('deleted_at')->count();

            // sla_hours
            $totalSlaStandardSubStage5 = ($subStage4->sla_hours ?? 0) * $totalDocumentsSubStage5;

            // 2. AMBIL DATA HISTORY
            $requestDocumentIdsCommittee = TspRequestDocument::where(function ($query) {
                $query->whereIn('stage_id', [7, 8, 9, 10, 11])
                    ->orWhereIn('substage_id', [6]);
            })
                ->whereNull('deleted_at')->pluck('id')->toArray();
            $historiesComittee = TspRequestDocumentHistory::whereIn('request_document_id', $requestDocumentIdsCommittee)
                ->whereNotNull('created_at')
                ->orderBy('request_document_id')
                ->orderBy('created_at')
                ->get()
                ->groupBy('request_document_id');

            // 3. KONFIGURASI STAGE DAN SUBSTAGE
            $conditionsComittee = [
                'stage_7' => fn($h) => (int) $h->stage_id === 7,
                'stage_8' => fn($h) => (int) $h->stage_id === 8,
                'stage_9' => fn($h) => (int) $h->stage_id === 9,
                'stage_10' => fn($h) => (int) $h->stage_id === 10,
                'stage_11' => fn($h) => (int) $h->stage_id === 11,
                'substage_6' => fn($h) => (int) $h->substage_id === 6,
            ];

            $totalActualSlaCommittee = 0;
            $slaSummary = [];

            $now = Carbon::now();


            // 4. HITUNG ACTUAL SLA
            foreach ($historiesComittee as $documentHistories) {

                $lastHistory = collect($conditionsComittee)
                    ->map(
                        fn($condition) => $documentHistories
                            ->filter($condition)
                            ->last()
                    )
                    ->filter()
                    ->sortBy('created_at')
                    ->last();

                if (!$lastHistory) {
                    continue;
                }

                $actualSla = round(
                    Carbon::parse($lastHistory->created_at)->diffInSeconds($now) / 3600,
                    2
                );

                $totalActualSlaCommittee += $actualSla;
            }

            // 5. HITUNG ACHIEVEMENT
            $achSlaCommittee = $totalActualSlaCommittee > 0
                ? round(($totalSlaStandardSubStage5 / $totalActualSlaCommittee) * 100, 2)
                : 0;

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pending review data.',
                'data' => [

                    'user_review' => $userReview,

                    'committee_review' => $committeeReview,

                    'bod_review' => $bodReview,
                    'ach_sla_user' => $achSlaUser,

                    'ach_sla_committee' => $achSlaCommittee,

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

            /*Total Pending Sign Customer*/
            $total = TspRequestDocument::where('sign_status', 'Partial Signed')
                ->whereNull('deleted_at')
                ->count();


            /*Top 5 Customer Pending Sign*/
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
                ->whereNull('documents.deleted_at')
                ->select(
                    'tsp_request_document_customers.name as customer_name',
                    DB::raw('COUNT(*) as pending_sign_count'),
                    'documents.created_at'
                )
                ->groupBy('tsp_request_document_customers.name')
                ->orderBy('pending_sign_count', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get();


            $data = [
                'total' => $total,

                'top_5_customers' => $topCustomers
                    ->map(function ($customer) {
                        return [
                            'customer_name' => $customer->customer_name,
                            'pending_sign_count' => (int) $customer->pending_sign_count,
                        ];
                    })
                    ->values(),
            ];


            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pending sign customer data.',
                'data' => $data
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

            $data = TspRequestDocument::select('site', DB::raw('COUNT(*) as total_contract'))
                ->whereNotIn('status_id', [1, 3, 4])
                ->whereNull('deleted_at')
                ->groupBy('site')
                ->get()
                ->map(function ($item) {
                    return [
                        'site' => $item->site,
                        'total_contract' => (int) $item->total_contract,
                    ];
                })
                ->values();

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


    public function getWaitingCustomer()
    {
        try {

            /*Total Pending Sign Customer*/
            $total = TspRequestDocument::where('stage_id', 4)
                ->whereNull('deleted_at')
                ->count();


            /*Top 5 Customer Pending Sign*/
            $topCustomers = TspRequestDocumentCustomer::join(
                'tsp_request_documents as documents',
                'tsp_request_document_customers.request_document_id',
                '=',
                'documents.id'
            )
                ->where(
                    'documents.stage_id',
                    4
                )
                ->whereNull('documents.deleted_at')
                ->select(
                    'tsp_request_document_customers.name as customer_name',
                    DB::raw('COUNT(*) as waiting_customer_count'),
                    'documents.created_at'
                )
                ->groupBy('tsp_request_document_customers.name')
                ->orderBy('waiting_customer_count', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get();


            $data = [
                'total' => $total,

                'top_5_customers' => $topCustomers
                    ->map(function ($customer) {
                        return [
                            'customer_name' => $customer->customer_name,
                            'waiting_customer_count' => (int) $customer->waiting_customer_count,
                        ];
                    })
                    ->values(),
            ];


            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved waiting customer data.',
                'data' => $data
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve waiting customer data.',
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
