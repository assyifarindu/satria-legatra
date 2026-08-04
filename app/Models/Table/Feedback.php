<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    
    protected $table = 'feedbacks';
    protected $fillable = [
        'request_document_id','document_id','feedback','file','created_by'
    ];

    public function RequestDocument() {
        return $this->belongsTo(RequestDocument::class);
    }

    public function Document() {
        return $this->belongsTo(Document::class);
    }
}
