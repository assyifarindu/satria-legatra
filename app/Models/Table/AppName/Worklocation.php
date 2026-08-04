<?php

namespace App\Models\Table\AppName;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worklocation extends Model
{
    use HasFactory;
    protected $table = 'mst_worklocation';
    protected $connection = 'mysql';
}
