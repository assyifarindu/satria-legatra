<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'note','start_alert','duration','department','created_by','created_at','updated_at'
    ];

    public function Document() {
        return $this->hasMany(Document::class);
    }

    public function BaseDocument() {
        return $this->hasMany(BaseDocument::class);
    }
}
