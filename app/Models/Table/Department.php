<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $connection = 'legatra';
    protected $fillable = [
        'short_name', 'name', 'company_codes'
    ];
}
