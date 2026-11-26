<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspDocumentCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_document_customers';

    protected $guarded = ['id'];

    public function document()
    {
        return $this->belongsTo(TspDocument::class, 'document_id');
    }

    public function customerPics()
    {
        return $this->hasMany(
            TspDocumentCustomerPic::class,
            'document_customer_id',
            'id'
        );
    }
}