<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';

    protected $table = 'tsp_documents';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */
}
