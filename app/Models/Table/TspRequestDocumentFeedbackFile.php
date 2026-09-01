<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentFeedbackFile extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_feedback_files';

    protected $fillable = [
        'request_document_feedback_id',
        'name',
        'file_path',
    ];

    public function requestDocumentFeedback()
    {
        return $this->belongsTo(TspRequestDocumentFeedback::class, 'request_document_feedback_id');
    }
}
