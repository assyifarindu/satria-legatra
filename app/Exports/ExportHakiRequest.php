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


class ExportHakiRequest implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $id_user = Auth::user()->id;
        $user = User::findOrFail($id_user);
        $company = $user->companyid;
       
        $query = RequestDocument::where('type', 'Haki')->orderBy('created_at', 'desc')->get();
        // $query = RequestDocument::join('satria.users', 'request_documents.created_by', '=', 'satria.users.id')
        // ->where('type', 'HAKI')->where('satria.users.companyid', $company)->orderBy('request_documents.created_at', 'desc')
        // ->whereYear('request_documents.created_at', '2025')
        // ->select('request_documents.*')->get();

        // foreach ($query as $key => $value) {
        //     $value->pic = VwPicDocument::where('document_id', $value->id)->get(['name']);
        //     $value->document_scope = DocumentScope::where('document_id', $value->id)->get(['department_name']);
         
        // }

        return view('export_request.haki', [
            'document' => $query
        ]);
    }
}
