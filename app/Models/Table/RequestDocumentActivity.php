<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDocumentActivity extends Model
{
    use HasFactory;

    protected $connection = 'legatra';

    protected $fillable = [
        'request_document_id','step_name','step','created_by'
    ];

    public function RequestDocument() {
        return $this->hasMany(RequestDocument::class);
    }
}
