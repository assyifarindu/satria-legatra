<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'mst_division';
    protected $fillable = [
        'nama',
        'divid',
        'company_id',
        'company_name',
        'divhead_name'
    ];
}
