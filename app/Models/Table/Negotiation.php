<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'request_document_id','document_id','note','file','created_by'
    ];

    public function RequestDocument() {
        return $this->belongsTo(RequestDocument::class);
    }

    public function Document() {
        return $this->belongsTo(Document::class);
    }
}
