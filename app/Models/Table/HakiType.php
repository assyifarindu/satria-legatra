<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakiType extends Model
{
    use HasFactory;
    protected $connection = 'legatra';

    protected $fillable = [
        'name','description'
    ];

    public function Document() {
        return $this->hasMany(Document::class);
    }

    public function DocumentHistory() {
        return $this->hasMany(DocumentHistory::class);
    }

    public function BaseDocument() {
        return $this->hasMany(BaseDocument::class);
    }
}
