<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseDocumentFinal extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'base_document_id', 'file'
    ];

    public function BaseDocument()
    {
        return $this->belongsTo(BaseDocument::class);
    }
}
