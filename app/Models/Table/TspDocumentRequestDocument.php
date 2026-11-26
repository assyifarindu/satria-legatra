<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspDocumentRequestDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';
    protected $table = 'tsp_document_request_documents';
    protected $guarded = ['id'];
}
