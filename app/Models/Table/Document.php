<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'contract_number', 'description', 'pic', 'category', 'contract_date', 'deal_date', 'duration', 'note', 'status', 'file', 'created_by',
        'created_at', 'updated_at', 'company', 'serial_number', 'priority', 'pic_nrp', 'pic_email', 'pic_name', 'updated_by', 'alert_id', 'is_extend',
        'request_document_id', 'revision', 'title_ringkasan', 'ringkasan', 'title_id', 'document_type', 'letter_type', 'letter_purpose', 'haki_type_id',
        'duty_id', 'launch_by', 'base_document_id', 'request_by', 'is_final', 'download_count', 'is_extend_automatically', 'is_unlimited_duration', 'duration_days',
        'end_contract_date', 'alert_days', 'id_worklocation', 'worklocation'
    ];

    public function Alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function ExtendedDocument()
    {
        return $this->belongsTo(ExtendedDocument::class);
    }

    public function DocumentHistory()
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function Feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function Negotiation()
    {
        return $this->hasMany(Negotiation::class);
    }

    public function Title()
    {
        return $this->belongsTo(Title::class);
    }

    public function DocumentActivityHistory()
    {
        return $this->hasMany(DocumentActivityHistory::class);
    }

    public function HakiType()
    {
        return $this->belongsTo(HakiType::class);
    }

    public function Duty()
    {
        return $this->belongsTo(Duty::class);
    }

    public function BaseDocument()
    {
        return $this->belongsTo(BaseDocument::class);
    }

    public function RequestDocument()
    {
        return $this->belongsTo(RequestDocument::class);
    }

    public function EmailAlert()
    {
        return $this->hasMany(EmailAlert::class);
    }

    public function DocumentFinal()
    {
        return $this->hasMany(DocumentFinal::class);
    }

    public function DocumentScope()
    {
        return $this->hasMany(DocumentScope::class);
    }

    public function PicDocument()
    {
        return $this->hasMany(PicDocument::class);
    }

    public function DocumentFinalAttachment()
    {
        return $this->hasMany(DocumentFinalAttachment::class);
    }
}
