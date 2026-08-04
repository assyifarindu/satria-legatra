<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestExistingDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'request_existing_id', 'title', 'file'
    ];

    public function RequestExisting()
    {
        return $this->belongsTo(RequestExisting::class);
    }
}
