<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtendDocumentActivityHistory extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    protected $table = 'extend_document_activity_histories';

    protected $fillable = [
        'extended_document_id','user_id','activity_name'
    ];

    public function ExtendedDocument() {
        return $this->belongsTo(ExtendedDocument::class);
    }
}
