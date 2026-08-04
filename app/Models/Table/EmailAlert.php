<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAlert extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'base_document_id','document_id','to','created_by','is_action'
    ];

    public function Document() {
        return $this->belongsTo(Document::class);
    }

    public function BaseDocument() {
        return $this->belongsTo(BaseDocument::class);
    }
}
