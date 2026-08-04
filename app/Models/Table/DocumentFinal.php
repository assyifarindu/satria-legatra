<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFinal extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'document_id','file','created_by'
    ];

    public function Document() {
        return $this->belongsTo(Document::class);
    }
       
}
