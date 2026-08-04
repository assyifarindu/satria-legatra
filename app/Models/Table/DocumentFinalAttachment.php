<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFinalAttachment extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'document_id', 'file'
    ];

    public function Document()
    {
        return $this->belongsTo(Document::class);
    }
}
