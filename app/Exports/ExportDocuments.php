<?php

namespace App\Exports;

use App\Models\Table\Document;
use App\Models\Table\DocumentScope;
use App\Models\Table\PicDocument;
use App\Models\View\VwPicDocument;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportDocuments implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $query = Document::where('is_extend', 1)->where('category', 1)->get(['*', 'created_at as pic', 'created_at as document_scope']);

        foreach ($query as $key => $value) {
            $value->pic = VwPicDocument::where('document_id', $value->id)->get(['name']);
            $value->document_scope = DocumentScope::where('document_id', $value->id)->get(['department_name']);
        }

        return view('export.document', [
            'document' => $query
        ]);
    }
}
