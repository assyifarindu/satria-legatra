<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentHistoryAttachment extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'document_history_id','file'
    ];

    public function DocumentHistory() {
        return $this->belongsTo(DocumentHistory::class);
    }
       
}
