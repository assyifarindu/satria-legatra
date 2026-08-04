<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestExistingHistory extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'request_existing_id','step_name','step','created_by'
    ];

    public function RequestExisting() {
        return $this->belongsTo(RequestExisting::class);
    }
}
