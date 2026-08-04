<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'company_id', 'email', 'name', 'user_id', 'is_email_notification'
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class);
    }

    public function RequestDocument()
    {
        return $this->hasMany(RequestDocument::class);
    }

    public function RequestExisting()
    {
        return $this->hasMany(RequestExisting::class);
    }
}
