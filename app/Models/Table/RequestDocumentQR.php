<?php

namespace App\Models\Table;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDocumentQR extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    protected $table = 'request_document_qrs';
    protected $with = ['user_request', 'user_verified'];
    protected $fillable = [
        'email', 'user_id', 'user_verified_id', 'file', 'status_verification', 'status_action', 'date_verification'
    ];

    public function user_request()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function user_verified()
    {
        return $this->hasOne(User::class, 'id', 'user_verified_id');
    }
}
