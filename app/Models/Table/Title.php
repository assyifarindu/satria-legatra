<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'name','code','is_delete'
    ];
    
    public function Document() {
        return $this->hasMany(Document::class);
    }

    public function DocumentHistory() {
        return $this->hasMany(DocumentHistory::class);
    }
}
