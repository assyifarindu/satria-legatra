<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtendedDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'extended_date','extend_contract_number','ref_document_number','serial_number','note',
        'alert_id','file','created_by','updated_by','created_at','updated_at','document_id'
    ];

    public function Alert() {
        return $this->belongsTo(Alert::class);
    }

    public function Document() {
        return $this->belongsTo(Document::class);
    }

    public function ExtendDocumentActivityHistory() {
        return $this->hasMany(ExtendDocumentActivityHistory::class);
    }
}
