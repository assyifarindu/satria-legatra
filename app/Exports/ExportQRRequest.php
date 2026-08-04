<?php

namespace App\Exports;

use App\Models\Table\RequestDocumentQR;
use App\Models\Table\Document;
use App\Models\User;
use App\Models\Table\PicDocument;
use App\Models\View\VwPicDocument;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportQRRequest implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
       
        $query =RequestDocumentQR::all();
        

        // foreach ($query as $key => $value) {
        //     $value->user_requester = User::where('id', $value->user_id)->get(['name']);
        //     $value->user_verified = User::where('id', $value->user_verified_id)->get(['name']);
        //     // $value->pic = VwPicDocument::where('document_id', $value->id)->get(['name']);
        //     // $value->document_scope = DocumentScope::where('document_id', $value->id)->get(['department_name']);
           
        // }

        return view('export_request.qr', [
            'document' => $query
        ]);
    }
}
