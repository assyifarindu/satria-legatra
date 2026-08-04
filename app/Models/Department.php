<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'mst_dept';
    protected $fillable = [
        'nama', 'div_code', 'div_name', 'company_id', 'company_name'
    ];
}
