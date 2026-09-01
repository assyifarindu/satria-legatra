<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentCommittees extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_committees';

    protected $fillable = [
        'request_document_id',
        'sequence',
        'committee_id',
        'verification_ld_status',
        'verification_flr_status',
        'created_by',
        'updated_by',
    ];

    public function requestDocument()
    {
        return $this->belongsTo(TspRequestDocument::class, 'request_document_id');
    }
}
