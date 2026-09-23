<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspFormLegalReview extends Model
{
    use HasFactory;

    protected $connection = 'legatra';
    public $timestamps = false;

    protected $table = 'tsp_form_legal_reviews';

    protected $fillable = [
        'request_document_id',
        'date',
        'department',
        'document_objective',
        'start_date',
        'end_date',
        'incoterm',
        'work_location',
        'delivery_location',
        'term_of_payment',
        'resume',
        'legal_note',
        'validation_required_by',
        'investment',
        'manpower_provision',
        'sanction',
        'penalty',
        'insurance',
        'sla',
        'version',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function requestDocument()
    {
        return $this->belongsTo(TspRequestDocument::class, 'request_document_id');
    }
}
