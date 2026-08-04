<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    
    protected $fillable = [
        'title','description','file','created_by','company_id','company_name'
    ];

    public function Company() {
        return $this->belongsTo(Company::class);
    }
}
