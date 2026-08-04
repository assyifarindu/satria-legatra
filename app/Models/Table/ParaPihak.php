<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParaPihak extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'request_document_id','name'
    ];

    public function RequestDocument() {
        return $this->belongsTo(RequestDocument::class);
    }
}
