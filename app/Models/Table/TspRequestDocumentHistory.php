<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_histories';

    protected $fillable = [
        'request_document_id',
        'stage_id',
        'substage_id',
        'status_id',
        'action',
        'created_by',
        'created_at',
    ];

    public function requestDocument()
    {
        return $this->belongsTo(TspRequestDocument::class, 'request_document_id');
    }

    public function stage()
    {
        return $this->belongsTo(TspStage::class, 'stage_id');
    }

    public function substage()
    {
        return $this->belongsTo(TspSubstage::class, 'substage_id');
    }

    public function status()
    {
        return $this->belongsTo(TspStatus::class, 'status_id');
    }


}