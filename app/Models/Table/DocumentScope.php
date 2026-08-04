<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentScope extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'document_id','base_document_id','request_document_id','department_code','department_name'
    ];

    public function Document() {
        return $this->belongsTo(Document::class);
    }

    public function RequestDocument() {
        return $this->belongsTo(RequestDocument::class);
    }

    public function BaseDocument() {
        return $this->belongsTo(BaseDocument::class);
    }
}
