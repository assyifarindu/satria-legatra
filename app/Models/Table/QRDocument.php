<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    protected $table = "qr_documents";
    protected $with = ['generateNumber'];
    protected $fillable = [
        'no_document',
        'date_document',
        'description',
        'receipent',
        'no_materai',
        'date_uploaded',
        'user_id',
        'user_sign',
        'qrcode_url',
        'id_attachment',
        'id_document_final',
        'doc_hash',
    ];

    public function generateNumber()
    {
        // return $this->belongsTo(GenerateNumber::class, 'no_document', 'id');

        return $this->belongsTo(GenerateNumber::class, 'no_document', 'id')->withDefault([
            'document_number' => 'N/A',
        ]);
    }
}
