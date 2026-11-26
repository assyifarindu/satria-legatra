<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_documents';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function pics()
    {
        return $this->hasOne(TspDocumentPic::class, 'document_id');
    }

    public function customer()
    {
        return $this->hasOne(TspDocumentCustomer::class, 'document_id');
    }

    public function files()
    {
        return $this->hasMany(TspDocumentFile::class, 'document_id');
    }

    public function committees()
    {
        return $this->hasMany(
            TspDocumentCommittee::class,
            'document_id',
            'id'
        )->where('deleted_at', null)->orderBy('sequence', 'asc');
    }
}
