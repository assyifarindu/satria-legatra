<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestExisting extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'base_document_id', 'purpose', 'status', 'position', 'is_deleted', 'created_by', 'pic_id', 'title', 'type', 'is_cancel'
    ];

    public function RequestExistingActivity()
    {
        return $this->hasMany(RequestExistingActivity::class);
    }

    public function BaseDocument()
    {
        return $this->belongsTo(BaseDocument::class);
    }

    public function Pic()
    {
        return $this->belongsTo(Pic::class);
    }

    public function RequestExistingHistory()
    {
        return $this->belongsTo(RequestExistingHistory::class);
    }

    public function RequestExistingDocument()
    {
        return $this->hasMany(RequestExistingDocument::class);
    }
}
