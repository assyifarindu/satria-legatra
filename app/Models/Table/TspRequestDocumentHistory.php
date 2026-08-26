<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentHistory extends Model
{
    use HasFactory;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_histories';

    public $timestamps = false;

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
        return $this->belongsTo(TspRequestStage::class, 'stage_id');
    }

    public function substage()
    {
        return $this->belongsTo(TspRequestSubstage::class, 'substage_id');
    }

    public function status()
    {
        return $this->belongsTo(TspRequestStatus::class, 'status_id');
    }
}
