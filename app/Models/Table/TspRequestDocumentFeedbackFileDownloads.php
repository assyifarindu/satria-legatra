<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentFeedbackFileDownloads extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';
    protected $table = 'tsp_request_document_feedback_file_downloads';
    protected $fillable = [
        'request_document_feedback_file_id',
        'download_by',
        'download_at',
        'created_by',
        'created_at'
    ];
}
