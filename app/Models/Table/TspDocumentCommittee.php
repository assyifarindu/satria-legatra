<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspDocumentCommittee extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_document_committees';

    protected $guarded = ['id'];

    public function document()
    {
        return $this->belongsTo(TspDocument::class, 'document_id');
    }
}
