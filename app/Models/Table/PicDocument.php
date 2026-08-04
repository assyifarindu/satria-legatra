<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicDocument extends Model
{
    use HasFactory;

    protected $connection = 'legatra';

    protected $fillable = [
        'document_id','user_id'
    ];

    public function Document() {
        return $this->belongsTo(Document::class);
    }
}
