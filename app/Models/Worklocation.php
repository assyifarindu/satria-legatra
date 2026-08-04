<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worklocation extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'mst_worklocation';
    protected $fillable = [
        'worklocation_code', 'worklocation_name', 'company_id'
    ];
}


