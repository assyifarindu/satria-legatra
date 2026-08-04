<?php

namespace App\Exports;

use App\Models\Table\RequestDocument;
use App\Models\Table\Document;
use App\Models\Table\DocumentScope;
use App\Models\Table\PicDocument;
use App\Models\View\VwPicDocument;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExportContractRequest implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $id_user = Auth::user()->id;
        $user = User::findOrFail($id_user);
        $company = $user->companyid;

        $query = RequestDocument::where('type', 'Contract')->orderBy('created_at', 'desc')->get();
        // $query = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
        // ->where('type', 'Contract')->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
        // ->whereYear('request_documents.created_at', '2026')
        // ->select('request_documents.*')->get();

        // foreach ($query as $key => $value) {
        //     $value->pic = VwPicDocument::where('document_id', $value->id)->get(['name']);
        //     $value->document_scope = DocumentScope::where('document_id', $value->id)->get(['department_name']);
         
        // }

        // Get data Contract 1 Januari 2025 - 30 Juni 2026
        $query = RequestDocument::from('request_documents as rd')
        ->join('satria.users as u', 'rd.created_by', '=', 'u.id')
        ->leftJoin(DB::raw("
            (
                SELECT *
                FROM (
                    SELECT
                        d.*,
                        ROW_NUMBER() OVER (
                            PARTITION BY d.request_document_id
                            ORDER BY
                                CASE
                                    WHEN d.contract_number IS NOT NULL
                                        AND TRIM(d.contract_number) <> ''
                                        AND TRIM(d.contract_number) <> '-'
                                    THEN 0
                                    ELSE 1
                                END,
                                d.id DESC
                        ) AS rn
                    FROM documents d
                ) x
                WHERE rn = 1
            ) d
        "), 'rd.id', '=', 'd.request_document_id')
        ->select(
            'rd.*',
            'd.contract_number'
        )
        ->where('rd.type', 'Contract')
        ->where('u.companyid', $company) // gunakan variabel
        // ->whereBetween('rd.created_at', ['2025-01-01', '2026-06-30 23:59:59'])
        ->orderByDesc('rd.created_at')
        ->get();


        return view('export_request.contract', [
            'document' => $query
        ]);
    }
}
