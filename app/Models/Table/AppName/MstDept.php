<?php

namespace App\Models\Table\AppName;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstDept extends Model
{
    use HasFactory;
    protected $table = 'mst_dept';
    protected $connection = 'mysql';
}
