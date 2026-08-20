<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TspRequestDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    protected $table = 'tsp_request_documents';
}
