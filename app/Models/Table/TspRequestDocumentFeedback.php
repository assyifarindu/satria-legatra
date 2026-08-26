<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TspRequestDocumentFeedback extends Model
{
    use HasFactory;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_feedbacks';

    public $timestamps = false;

    protected $fillable = [
        'request_document_id',
        'history_id',
        'stage_id',
        'substage_id',
        'remark'
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

    public function history()
    {
        return $this->belongsTo(TspRequestDocumentHistory::class, 'history_id');
    }
}
