<?php

namespace App\Exports;

use App\Models\Table\RequestExisting;
use App\Models\Table\Document;
use App\Models\Table\DocumentScope;
use App\Models\Table\PicDocument;
use App\Models\View\VwPicDocument;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportExistingRequest implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
       
        // $query = RequestExisting::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        $query = [
            'request' => RequestExisting::where('is_deleted', false)->orderBy('created_at', 'desc')->get()
        ];

        // foreach ($query as $key => $value) {
        //     $value->pic = VwPicDocument::where('document_id', $value->id)->get(['name']);
        //     $value->document_scope = DocumentScope::where('document_id', $value->id)->get(['department_name']);
           
        // }

        return view('export_request.existing', [
            'document' => $query
        ]);
    }
}
