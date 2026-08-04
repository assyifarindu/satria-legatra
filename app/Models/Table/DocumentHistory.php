<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentHistory extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    
    protected $fillable = [
        'contract_number','description','pic','category','company_id','company','contract_date','deal_date','duration','note','status','file','request_document_id','created_by',
        'serial_number','priority','pic_nrp','pic_email','pic_name','updated_by','alert_id','document_id','revision' ,'title_id','document_type','letter_type','letter_purpose','haki_type_id',
        'duty_id','launch_by'
    ];

    public function Document() {
        return $this->belongsTo(Document::class);
    }

    public function Title() {
        return $this->belongsTo(Title::class);
    }

    public function HakiType() {
        return $this->belongsTo(HakiType::class);
    }

    public function Duty() {
        return $this->belongsTo(Duty::class);
    }

    public function DocumentHistoyAttacment() {
        return $this->hasMany(DocumentHistoryAttachment::class);
    }
}
