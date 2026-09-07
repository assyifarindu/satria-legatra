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
        'pic',
        'department',
        'party_name',
        'document_number',
        'document_objective',
        'period_time',
        'incoterm',
        'work_location',
        'delivery_location',
        'term_of_payment',
        'resume',
        'legal_note'
    ];

    public function requestDocument()
    {
        return $this->belongsTo(TspRequestDocument::class, 'request_document_id');
    }
}
