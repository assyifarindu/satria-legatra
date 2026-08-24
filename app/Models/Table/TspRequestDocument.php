<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_documents';

    protected $fillable = [
        'stage_id',
        'substage_id',
        'status_id',
        'document_number',
        'title',
        'contract_type',
        'requester_id',
        'potential_amount',
        'sign_status',
        'is_project',
        'sow',
        'transaction_procedure',
        'kpi',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_project' => 'boolean',
        'potential_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

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

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function pics()
    {
        return $this->hasOne(TspRequestDocumentPic::class, 'request_document_id');
    }

    public function customer()
    {
        return $this->hasOne(TspRequestDocumentCustomer::class, 'request_document_id');
    }

    public function files()
    {
        return $this->hasMany(TspRequestDocumentFile::class, 'request_document_id');
    }

    public function committees()
    {
        return $this->hasMany(TspRequestDocumentCommittee::class, 'request_document_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(TspRequestDocumentFeedback::class, 'request_document_id');
    }

    public function histories()
    {
        return $this->hasMany(TspRequestDocumentHistory::class, 'request_document_id');
    }

    public function formLegalReview()
    {
        return $this->hasOne(TspFormLegalReview::class, 'request_document_id');
    }
}