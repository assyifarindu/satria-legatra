<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    
    protected $fillable = [
        'name', 'short_name'
    ];

    public function Pic() {
        return $this->hasMany(Pic::class);
    }

    public function Template() {
        return $this->hasMany(Template::class);
    }
}
