<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'contract_number', 'description', 'pic', 'category', 'contract_date', 'deal_date', 'duration', 'note', 'status', 'file', 'created_by',
        'created_at', 'updated_at', 'company', 'serial_number', 'priority', 'pic_nrp', 'pic_email', 'pic_name', 'updated_by', 'alert_id', 'is_extend',
        'request_document_id', 'revision', 'title_ringkasan', 'ringkasan', 'title_id', 'document_type', 'letter_type', 'letter_purpose', 'haki_type_id',
        'duty_id', 'launch_by', 'is_proceed', 'last_request_by', 'start_alert_date', 'end_contract_date', 'is_extend_automatically', 'is_unlimited_duration',
        'duration_days', 'alert_days', 'deleted_by', 'deleted_at', 'deleted_by_name', 'id_worklocation', 'worklocation'
    ];

    public function Document()
    {
        return $this->hasMany(Document::class);
    }

    public function BaseDocumentActivity()
    {
        return $this->hasMany(BaseDocumentActivity::class);
    }

    public function Alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function HakiType()
    {
        return $this->belongsTo(HakiType::class);
    }

    public function Duty()
    {
        return $this->belongsTo(Duty::class);
    }

    public function EmailAlert()
    {
        return $this->hasMany(EmailAlert::class);
    }

    public function DocumentScope()
    {
        return $this->hasMany(DocumentScope::class);
    }

    public function RequestDocument()
    {
        return $this->belongsTo(RequestDocument::class);
    }

    public function BaseDocumentFinal()
    {
        return $this->hasMany(BaseDocumentFinal::class);
    }
}
