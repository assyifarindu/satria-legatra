<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseDocumentActivity extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'base_document_id','user_id','activity_name'
    ];

    public function BaseDocument() {
        return $this->belongsTo(BaseDocument::class);
    }
}
