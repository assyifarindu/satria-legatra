<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'type', 'title', 'para_pihak', 'scope', 'email', 'status', 'file', 'created_by', 'pic_id', 'status_license', 'is_extend', 'base_document_id', 'is_cancel', 'is_extend_automatically', 'is_unlimited_duration', 'note'
    ];

    public function RequestDocumentActivity()
    {
        return $this->hasMany(RequestDocumentActivity::class);
    }

    public function Negotiation()
    {
        return $this->hasMany(Negotiation::class);
    }

    public function Document()
    {
        return $this->hasMany(Document::class);
    }

    public function Feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function ParaPihak()
    {
        return $this->hasMany(ParaPihak::class);
    }

    public function UploadDocument()
    {
        return $this->hasMany(UploadDocument::class);
    }

    public function Pic()
    {
        return $this->belongsTo(Pic::class);
    }

    public function DocumentScope()
    {
        return $this->hasMany(DocumentScope::class);
    }

    public function BaseDocument()
    {
        return $this->hasMany(BaseDocument::class);
    }
}
