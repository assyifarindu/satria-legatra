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

            $pendingLegal = TspRequestDocument::whereIn('stage_id', [2, 3])
                ->count();

            // ========================================
            // 1. TOTAL SLA STANDARD
            // ========================================

            $stage2 = TspRequestStage::find(2);
            $stage3 = TspRequestStage::find(3);

            $totalDocumentsStage2 = TspRequestDocument::where('stage_id', 2)->count();
            $totalDocumentsStage3 = TspRequestDocument::where('stage_id', 3)->count();

            // sla_hours
            $totalSlaStandardStage2 = ($stage2->sla_hours ?? 0) * $totalDocumentsStage2;

            $totalSlaStandardStage3 = ($stage3->sla_hours ?? 0) * $totalDocumentsStage3;

            $totalSlaStandard = $totalSlaStandardStage2 + $totalSlaStandardStage3;


            // ========================================
            // 2. AMBIL DATA HISTORY
            // ========================================

            $histories = TspRequestDocumentHistory::whereIn('stage_id', [2, 3])
                ->whereNotNull('created_at')
                ->orderBy('request_document_id')
                ->orderBy('created_at')
                ->get()
                ->groupBy('request_document_id');

            // ========================================
            // 3. HITUNG ACTUAL SLA PER DOKUMEN
            // ========================================

            $totalActualSlaStage2 = 0;
            $totalActualSlaStage3 = 0;

            $countActualStage2 = 0;
            $countActualStage3 = 0;

            $today = Carbon::today();

            foreach ($histories as $documentId => $documentHistories) {

                // History sudah diurutkan berdasarkan created_at ascending.
                $documentHistories = $documentHistories->values();

                // Ambil history Stage 2 paling terakhir
                $lastStage2 = $documentHistories
                    ->filter(fn($history) => (int) $history->stage_id === 2)
                    ->last();

                // Ambil history Stage 3 paling terakhir
                $lastStage3 = $documentHistories
                    ->filter(fn($history) => (int) $history->stage_id === 3)
                    ->last();

                // Ambil stage terakhir dari Stage 2 atau Stage 3
                // berdasarkan created_at yang paling baru.
                $lastHistory = collect([$lastStage2, $lastStage3])
                    ->filter()
                    ->sortBy('created_at')
                    ->last();

                if (!$lastHistory) {
                    continue;
                }

                $startDate = Carbon::parse($lastHistory->created_at);
                $actualSla = (int) $startDate->startOfDay()->diffInDays($today);

                if ((int) $lastHistory->stage_id === 2) {
                    $totalActualSlaStage2 += $actualSla;
                    $countActualStage2++;
                } elseif ((int) $lastHistory->stage_id === 3) {
                    $totalActualSlaStage3 += $actualSla;
                    $countActualStage3++;
                }
            }

            // ========================================
            // 4. TOTAL ACTUAL SLA DAN ACHIEVEMENT
            // ========================================

            $totalActualSla =
                $totalActualSlaStage2 + $totalActualSlaStage3;

            $achSlaLegal = $totalActualSla > 0
                ? round(($totalSlaStandard / $totalActualSla) * 100, 2)
                : 0;

            $agingDueDateAggrement = TspFormLegalReview::join('tsp_request_documents as documents', 'tsp_form_legal_reviews.request_document_id', '=', 'documents.id')
                ->whereIn('documents.stage_id', [2, 3])
                ->whereNotNull('tsp_form_legal_reviews.end_date')
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

            $userReview = TspRequestDocument::whereIn('substage_id', [1, 4])
                ->count();

            $committeeReview = TspRequestDocument::whereIn('substage_id', [2, 5])
                ->count();

            $bodReview = TspRequestDocument::where('stage_id', 7)
                ->count();


            // HITUNG SLA ACHIEVEMENT USER
            // 1. TOTAL SLA STANDARD

            $subStage1 = TspRequestSubstage::find(1);
            $subStage4 = TspRequestSubstage::find(4);

            $totalDocumentsSubStage1 = TspRequestDocument::where('substage_id', 1)->count();
            $totalDocumentsSubStage4 = TspRequestDocument::where('substage_id', 4)->count();

            // sla_hours
            $totalSlaStandardSubStage1 = ($subStage1->sla_hours ?? 0) * $totalDocumentsSubStage1;

            $totalSlaStandardSubStage4 = ($subStage4->sla_hours ?? 0) * $totalDocumentsSubStage4;

            $totalSlaStandard = $totalSlaStandardSubStage1 + $totalSlaStandardSubStage4;


            // 2. AMBIL DATA HISTORY

            $histories = TspRequestDocumentHistory::whereIn('substage_id', [1, 4])
                ->whereNotNull('created_at')
                ->orderBy('request_document_id')
                ->orderBy('created_at')
                ->get()
                ->groupBy('request_document_id');

            // 3. HITUNG ACTUAL SLA PER DOKUMEN

            $totalActualSlaSubStage1 = 0;
            $totalActualSlaSubStage4 = 0;

            $countActualSubStage1 = 0;
            $countActualSubStage4 = 0;
            $today = Carbon::today();

            foreach ($histories as $documentId => $documentHistories) {

                // History sudah diurutkan berdasarkan created_at ascending.
                $documentHistories = $documentHistories->values();

                // Ambil history SubStage 1 paling terakhir
                $lastSubStage1 = $documentHistories
                    ->filter(fn($history) => (int) $history->substage_id === 1)
                    ->last();

                // Ambil history SubStage 4 paling terakhir
                $lastSubStage4 = $documentHistories
                    ->filter(fn($history) => (int) $history->substage_id === 4)
                    ->last();

                // Ambil stage terakhir dari SubStage 1 atau SubStage 4 berdasarkan created_at yang paling baru.
                $lastHistory = collect([$lastSubStage1, $lastSubStage4])
                    ->filter()
                    ->sortBy('created_at')
                    ->last();

                if (!$lastHistory) {
                    continue;
                }

                $startDate = Carbon::parse($lastHistory->created_at);
                $actualSla = (int) $startDate->startOfDay()->diffInDays($today);

                if ((int) $lastHistory->substage_id === 1) {
                    $totalActualSlaSubStage1 += $actualSla;
                    $countActualSubStage1++;
                } elseif ((int) $lastHistory->substage_id === 4) {
                    $totalActualSlaSubStage4 += $actualSla;
                    $countActualSubStage4++;
                }
            }

            // 4. TOTAL ACTUAL SLA DAN ACHIEVEMENT

            $totalActualSlaUser = $totalActualSlaSubStage1 + $totalActualSlaSubStage4;

            $achSlaUser = $totalActualSlaUser > 0
                ? round(($totalSlaStandard / $totalActualSlaUser) * 100, 2)
                : 0;

            // HITUNG SLA ACHIEVEMENT COMMITTEE
            // 1. TOTAL SLA STANDARD

            $subStage1 = TspRequestSubstage::find(2);
            $subStage4 = TspRequestSubstage::find(5);

            $totalDocumentsSubStage2 = TspRequestDocument::where('substage_id', 2)->count();
            $totalDocumentsSubStage5 = TspRequestDocument::where('substage_id', 5)->count();

            // sla_hours
            $totalSlaStandardSubStage2 = ($subStage1->sla_hours ?? 0) * $totalDocumentsSubStage2;
            $totalSlaStandardSubStage5 = ($subStage4->sla_hours ?? 0) * $totalDocumentsSubStage5;

            $totalSlaStandard = $totalSlaStandardSubStage2 + $totalSlaStandardSubStage5;

            // 2. AMBIL DATA HISTORY
            $histories = TspRequestDocumentHistory::whereIn('substage_id', [2, 5])
                ->whereNotNull('created_at')
                ->orderBy('request_document_id')
                ->orderBy('created_at')
                ->get()
                ->groupBy('request_document_id');

            // 3. HITUNG ACTUAL SLA PER DOKUMEN

            $totalActualSlaSubStage2 = 0;
            $totalActualSlaSubStage5 = 0;

            $countActualSubStage2 = 0;
            $countActualSubStage5 = 0;
            $today = Carbon::today();

            foreach ($histories as $documentId => $documentHistories) {

                // History sudah diurutkan berdasarkan created_at ascending.
                $documentHistories = $documentHistories->values();

                // Ambil history SubStage 2 paling terakhir
                $lastSubStage2 = $documentHistories
                    ->filter(fn($history) => (int) $history->substage_id === 2)
                    ->last();

                // Ambil history SubStage 5 paling terakhir
                $lastSubStage5 = $documentHistories
                    ->filter(fn($history) => (int) $history->substage_id === 5)
                    ->last();

                // Ambil stage terakhir dari SubStage 2 atau SubStage 5
                // berdasarkan created_at yang paling baru.
                $lastHistory = collect([$lastSubStage2, $lastSubStage5])
                    ->filter()
                    ->sortBy('created_at')
                    ->last();

                if (!$lastHistory) {
                    continue;
                }

                $startDate = Carbon::parse($lastHistory->created_at);
                $actualSla = (int) $startDate->startOfDay()->diffInDays($today);

                if ((int) $lastHistory->substage_id === 2) {
                    $totalActualSlaSubStage2 += $actualSla;
                    $countActualSubStage2++;
                } elseif ((int) $lastHistory->substage_id === 5) {
                    $totalActualSlaSubStage5 += $actualSla;
                    $countActualSubStage5++;
                }
            }

            // 4. TOTAL ACTUAL SLA DAN ACHIEVEMENT

            $totalActualSlaCommittee = $totalActualSlaSubStage2 + $totalActualSlaSubStage5;

            $achSlaCommittee = $totalActualSlaCommittee > 0
                ? round(($totalSlaStandard / $totalActualSlaCommittee) * 100, 2)
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
                ->select(
                    'tsp_request_document_customers.name as customer_name',
                    DB::raw('COUNT(*) as pending_sign_count')
                )
                ->groupBy('tsp_request_document_customers.name')
                ->orderByDesc('pending_sign_count')
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
}
