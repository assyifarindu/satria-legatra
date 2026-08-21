<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_customers';

    protected $fillable = [
        'request_document_id',
        'name',
        'nib',
        'npwp',
        'address',
        'postal_code',
        'customer_group',
        'email',
        'created_by',
        'updated_by',
    ];

    public function requestDocument()
    {
        return $this->belongsTo(TspRequestDocument::class, 'request_document_id');
    }


}