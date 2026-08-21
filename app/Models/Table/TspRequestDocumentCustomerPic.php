<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspRequestDocumentCustomerPic extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_request_document_customer_pics';

    protected $fillable = [
        'request_document_customer_id',
        'name',
        'position',
        'email',
        'phone',
        'created_by',
        'updated_by',
    ];

    public function requestDocumentCustomer()
    {
        return $this->belongsTo(TspRequestDocumentCustomer::class, 'request_document_customer_id');
    }


}